// **GAME CONSTANTS**

/*
const GameCookieService = {

    setCookie(name, value, days) {
        let expires = '';
s
        if (days) {
            const date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = '; expires=' + date.toUTCString();
        }

       // document.cookie = name + '=' + (value || '')  + expires + ';';
        document.cookie = name+"="+ (value || '') + expires+"; path=/";
    },

    getCookie(name) {
        const cookies = document.cookie.split(';');

        for (const cookie of cookies) {
            if (cookie.indexOf(name + '=') > -1) {
                return cookie.split('=')[1];
            }
        }

        return null;
    }
}
*/

//replace cookies w/ local storage
const GameStorageService = {

    setItem(name, value, days) {
        const item = {
            value: value,
            expiry: days ? new Date().getTime() + days * 24 * 60 * 60 * 1000 : null
        };
        localStorage.setItem(name, JSON.stringify(item));
    },

    getItem(name) {
        const itemStr = localStorage.getItem(name);

        if (!itemStr) return null;

        const item = JSON.parse(itemStr);

        if (item.expiry && new Date().getTime() > item.expiry) {
            localStorage.removeItem(name);
            return null;
        }

        return item.value;
    },

    removeItem(name) {
        localStorage.removeItem(name);
    }
};


// **DOM Element Selections**
const pageHeader = document.getElementById('header-section');
const titleScreen = document.getElementById('title-screen');
const gameScreen = document.getElementById('game-screen');
const gameGridElement = document.getElementById('sortable-grid');
const finalScoreScreen = document.getElementById('final-score-screen');
const finalScoreToggle = document.getElementById('final-page-toggle');
const finalPoem = document.getElementById('final-poem');
const skinToggleButton = document.getElementById('skin-toggle-button');
const gameMenuButton = document.getElementById('game-menu-btn');
const finalMoveCount = document.getElementById('final-move-count');
const startButton = document.getElementById('start-button');
const playGameButton = document.getElementById('play-game');
const playAgain = document.getElementById('play-again');
const shareBtn = document.getElementById('share-button');
const shareBtnSend = document.getElementById('share-send');
const shareBtnClose = document.getElementById('share-close');
const shareModal = document.getElementById('share-modal');
const errorCounterDisplay = document.getElementById('error-counter'); // Move counter element
const moveCounterDisplay = document.getElementById('move-counter'); // Move counter element
const sentenceCounterDisplay = document.getElementById('sentence-counter'); // Move counter element
const counterContainer = document.getElementById('sentence-list-container');
const counterContainerContent = document.getElementById('sentence-list-text');
//const sentenceToggle = document.getElementById('sentence-toggle');
const sentenceToggle = document.querySelectorAll('.sentence-toggle');
const closeSentenceToggle = document.getElementById('close-sentences-button');
const titleDisplay = document.getElementById('puzzle-title'); // Move counter element
const fullPoem = document.getElementById('full-poem'); // Move counter element
const poemColumn1 = document.getElementById('poem-column1'); // Move counter element
const poemColumn2 = document.getElementById('poem-column2'); // Move counter element
const poemColumn3 = document.getElementById('poem-column3'); // Move counter element

// **Game State Variables**
let currentPuzzleID = 0;
let moveCounterValue = 0; // Track the number of total moves
let incorrectCounterValue = 0; // Track the number of incorrect moves
let activePoem = [];
let sentences = []; // Store sentence data from the JSON
let completedSentences = []; // Track completed sentences
let completeSentenceCount = 0; // Track completed sentences
let totalSentenceCount = 0; // Track completed sentences
let completedColumns = []; // Track completed columns
let minimumMoves = 0; // Store minimum moves required
let originalPositions = []; // Store the initial positions of words
let lockedWords = []; // Store the initial positions of words
let lockedPuzzleWords = [];
let title = ''; // Store the title of the puzzle
let puzzleCounter = 0;
let gameGrid = null;
let completedSessionPuzzles = [];

if (GameStorageService.getItem('ww-session-games')) {
    completedSessionPuzzles = GameStorageService.getItem('ww-session-games');
}

console.log('WW puzzles completed: ' + completedSessionPuzzles);

let completedPuzzleRank = '';
let completedPuzzleIcon = '';
let isFirstPlay = 1;
let toggled = 'review';

let columnCompletedThisMove = false; // Flag for column completion
let sentenceCompletedThisMove = false; // Flag for sentence completion

document.addEventListener('DOMContentLoaded', initializeGame);

function initializeGame() {

    if (GameStorageService.getItem('ww-played-before')) {
        isFirstPlay = 0;
    } else {
        //GameCookieService.setCookie('ww-played-before',1,365);
        GameStorageService.setItem('ww-played-before', 1, 1);
    }

    playGameButton.addEventListener('click', () => {
        if (isFirstPlay == 1) {
            jQuery('#help-button').trigger('click');
        }

        titleScreen.style.display = 'none';
        //gameScreen.style.display = 'flex';
        startGame(puzzleCounter);
    });

    playAgain.addEventListener('click', () => {
        instructionScreen.style.display = 'none';
        finalScoreScreen.style.display = 'none';
        gameGridElement.innerHTML = '<span></span><img src="/wp-content/themes/wallawords/assets/build/images/spinner.svg" class="loading"><span></span>';
        startGame(puzzleCounter);
    });

    shareBtn.addEventListener('click', () => {

        //let phone = document.getElementById("share-phone").value;
        //let message = document.getElementById("share-message").value;		
        //let smsLink = `sms:${phone}?&body=${message}`;

        var message = `I just discovered the amazing game, WallaWords! I solved it in ${moveCounterValue} moves and earned the “${completedPuzzleIcon} ${completedPuzzleRank}” level. Think you can beat me? Try it here - https://wallawords.com/play`;

        let smsLink = `sms:?&body=${message}`;
        window.location.href = smsLink;
    });

    sentenceToggle[0].addEventListener('click', () => {

        var container = jQuery('#sentence-list-container');

        if (container.css('display') == 'none') {
            container.fadeIn(200);
            errorCounterDisplay.style.display = "none";
            moveCounterDisplay.style.display = "none";
            sentenceCounterDisplay.style.display = "block";
            sentenceToggle[0].style.display = "none";
        } else {
            container.fadeOut(100);
            errorCounterDisplay.style.display = "block";
            moveCounterDisplay.style.display = "block";
            sentenceToggle[0].style.display = "block";
        }

    });

    sentenceToggle[1].addEventListener('click', () => {

        var container = jQuery('#sentence-list-container');

        if (container.css('display') == 'none') {
            container.fadeIn(200);
            //moveCounterDisplay.style.display = "none";
            sentenceCounterDisplay.style.display = "block";
            sentenceToggle[1].style.display = "none";
        } else {
            container.fadeOut(100);
            sentenceCounterDisplay.style.display = "none";
            sentenceToggle[1].style.display = "block";
        }

    });

    closeSentenceToggle.addEventListener('click', () => {
        var container = jQuery('#sentence-list-container');
        container.fadeOut(150);

        if (finalScoreScreen.style.display == 'none') {

            moveCounterDisplay.style.display = "block";
            sentenceToggle.forEach(item => {
                item.style.display = "block";
            });

        } else {
            sentenceCounterDisplay.style.display = "none";
            sentenceToggle[1].style.display = "block";
        }

    });

}

// **Function to Remove Residual Classes After Dragging**
function removeDragClasses() {
    const items = document.querySelectorAll('.grid-item');
    items.forEach(item => {
        item.classList.remove('sortable-ghost', 'dragging', 'sortable-swap-highlight');
    });
}

// **Remove Drop-Zone Class from All Items**
function removeDropZoneClass() {
    const items = document.querySelectorAll('.grid-item');
    items.forEach(item => {
        item.classList.remove('drop-zone');
    });
}

// **Remove Animate-In class from All Items**
function removeAnimateInClass() {
    const items = document.querySelectorAll('.grid-item');
    items.forEach(item => {
        item.classList.remove('intro1', 'intro2');
    });
}


function removeBounceClass() {
    const items = document.querySelectorAll('.grid-item');
    items.forEach(item => {
        item.classList.remove('start-position');
    });
}

async function dpData(encryptedDataWithIv, nonce) {
    const encryptedBytes = Uint8Array.from(atob(encryptedDataWithIv), c => c.charCodeAt(0));

    const iv = encryptedBytes.slice(0, 16);
    const ciphertext = encryptedBytes.slice(16);

    const encoder = new TextEncoder();
    const nonceBytes = encoder.encode(nonce);
    const hashBuffer = await crypto.subtle.digest('SHA-256', nonceBytes);

    const key = await crypto.subtle.importKey(
        'raw',
        hashBuffer,
        { name: 'AES-CBC' },
        false,
        ['decrypt']
    );

    const decryptedBuffer = await crypto.subtle.decrypt(
        { name: 'AES-CBC', iv: iv },
        key,
        ciphertext
    );

    const decoder = new TextDecoder();
    const jsonString = decoder.decode(decryptedBuffer);

    return JSON.parse(jsonString);
}

// **Start the Game**
async function startGame(puzzleCounterValue) {

    pageHeader.classList.add('playing');
    moveCounterDisplay.classList.remove('over');

    finalScoreScreen.style.display = 'none';
    moveCounterDisplay.style.display = "block";
    sentenceCounterDisplay.style.display = "block";
    errorCounterDisplay.style.display = "block";
    errorCounterDisplay.innerHTML = '';

    if (jQuery('#puzzle_id').length) {
        hasID = jQuery('#puzzle_id').val();
        url = localVars.ajax_url + '?action=wallawords_get_puzzle_data&gameID=' + hasID + '&nonce=' + localVars.nonce;
        currentPuzzleID = hasID;
    } else {
        url = localVars.ajax_url + '?action=wallawords_get_puzzle_data&nonce=' + localVars.nonce;
    }

    if (completedSessionPuzzles) {
        url += '&completed=' + completedSessionPuzzles;
    }

    console.log(url);

    gameScreen.style.display = 'flex';

    const floatingPieces = document.querySelectorAll('.piece');

    floatingPieces.forEach((piece) => {
        piece.classList.add('blur'); //change floating elements
    });

    //reset values on start
    moveCounterValue = 0;
    completeSentenceCount = 0;
    incorrectCounterValue = 0;
    completedSentences = [];
    completedColumns = [];

    if (gameGrid) {
        gameGrid.destroy();

        const resetSentenceCounter = document.querySelectorAll('.sentence-list-item');
        resetSentenceCounter.forEach((row) => {
            row.remove();
        });
    }

    jQuery.ajax({
        url: url,
        type: 'GET',
        success: async function (response) {
            let data = response;
            if (typeof response === "string") {
                try {
                    data = JSON.parse(response);
                } catch (err) {
                    console.error("❌ JSON.parse failed:", err, response);
                    return;
                }
            }

            if (data.pd) {
                 try {
                    await dpData(data.pd, localVars.nonce);
                } catch (err) {
                    console.error("❌ Decryption failed:", err);
                }
                // console.log(JSON.parse(response)); Get All Puzzles data
                const puzzles = await dpData(data.pd, localVars.nonce);
                console.log(puzzles);
                // const puzzles = JSON.parse(test);

                sentenceToggle.forEach(item => {
                    item.style.display = "block";
                });

                const originalDropPlacement = new Map(); // Map to store initial positions

                // Select a random puzzle
                const selectedPoem = puzzles[puzzleCounterValue];
                activePoem = selectedPoem;

                currentPuzzleID = selectedPoem.id; //poem ID
                title = selectedPoem.title; // Store the puzzle title
                originalPositions = selectedPoem.correctWords.slice(); // Store the original positions
                lockedWords = selectedPoem.lockedWords.slice(); // Store the locked positions
                sentences = selectedPoem.sentences; // Store sentences from JSON
                columns = selectedPoem.columns; //store column rows
                totalSentenceCount = selectedPoem.sentences.length + 3; //add 3 because we always have 3 vertical sentences in a puzzle

                // console.log('sentences = '.selectedPoem.sentences+"~"+selectedPoem.sentences.length);
                //console.log('sentences = ');
                //console.dir(selectedPoem.sentences);

                // Define the solved state explicitly
                const solvedState = originalPositions.slice(); // Assumes solved state is the initial state

                // Shuffle the remaining words using derangement
                const lockedIndexes = selectedPoem.lockedWords;
                const incorrectWords = selectedPoem.incorrectWords;

                titleDisplay.textContent = selectedPoem.title;

                gameGridElement.innerHTML = ''; // Clear previous words

                originalPositions.forEach((word, index) => {
                    const div = document.createElement('div');
                    div.classList.add('grid-item');
                    if (lockedIndexes.includes(index)) {
                        div.textContent = word;
                        div.classList.add('correct-position'); // Mark it as correctly placed
                        div.classList.add('locked-position'); // Mark it as locked
                        div.classList.add('intro1');
                        div.style.animationDelay = `${parseInt(index) * .01}s`; //add delay to float in effect to stagger tiles  
                    } else {
                        div.textContent = incorrectWords.shift(); // Populate with shuffled words
                        div.classList.add('intro2');
                        div.classList.add('start-position');
                        var rand = parseInt(Math.floor(Math.random() * (originalPositions.length - 0 + 1) + 0));
                        var randDelay = (rand * .01) + ((parseInt(index) * .01) * lockedIndexes.length);
                        //console.log(randDelay);
                        div.style.animationDelay = `${randDelay}s`; //add delay to float in effect to stagger tiles                      
                    }

                    gameGridElement.appendChild(div);
                });


                //get original placements as an array object to compare
                document.querySelectorAll('.grid-item').forEach((tile) => {
                    const parent = tile.parentElement;
                    const index = Array.from(parent.children).indexOf(tile);
                    originalDropPlacement.set(tile, { parent, index });
                });

                // Calculate the minimum number of moves required using cycle decomposition method
                minimumMoves = calculateMinSwapsUsingGraphMethod(solvedState);

                console.log(`Lowest possible is ${minimumMoves} moves`); // Log minimum moves to console

                // Initialize SortableJS on the grid container with Swap plugin
                gameGrid = new Sortable(gameGridElement, {
                    animation: 150,
                    ghostClass: 'sortable-ghost',
                    chosenClass: 'dragging',
                    dragClass: 'sortable-drag',
                    fallbackOnBody: true,
                    forceFallback: true,
                    swap: true,
                    swapClass: 'sortable-swap-highlight',
                    revertOnSpill: true,
                    swapThreshold: 0.1,
                    // Called by any change to the list (add / update / remove)
                    onSort: function (evt) {
                        updateMoveCounter(evt, originalPositions);
                        // Remove unnecessary classes to ensure draggable functionality
                        removeDragClasses();
                        //console.log('trigger sort');
                    },
                    onMove: function (evt) {
                        removeDropZoneClass();
                        if (evt.related && !evt.related.classList.contains('locked-position')) {
                            evt.related.classList.add('drop-zone');
                            //console.log('trigger drop zone');
                        } else {
                            evt.related.classList.remove('drop-zone');
                        }
                    },
                    onUnchoose: function (evt) {
                        removeDropZoneClass();
                        //console.log('unchosen');
                    },
                    onEnd: function (evt) {
                        const item = evt.item;
                        const { parent, index } = originalDropPlacement.get(item);

                        //console.log('end');

                        // Check if the item was placed in an invalid slot
                        // if (!item.parentElement.classList.contains('valid-slot')) {
                        //parent.insertBefore(item, parent.children[index]); // Revert to original position
                        //}

                        removeDropZoneClass();
                    }

                });

                /* counter grids */

                //set default sentence counters
                for (s = 1; s <= totalSentenceCount; s++) {
                    const div = document.createElement('div');
                    div.id = 'sentence_' + s;
                    div.classList.add('sentence-list-item');
                    div.innerHTML = '<div class="content">' + s + '. sentence not found</div>';

                    const grid = document.createElement('div');
                    grid.id = 'grid_' + s;
                    grid.classList.add('grid-container');

                    for (c = 0; c < 15; c++) {
                        const gridCell = document.createElement('div');
                        gridCell.classList.add('grid-cell');
                        grid.append(gridCell);
                    }

                    div.prepend(grid);

                    counterContainerContent.appendChild(div);
                }

                updateMoveCounterDisplay(); //set 0 of max sentences

                //remove intro animation classes
                setTimeout(() => {
                    removeAnimateInClass();
                    setTimeout(() => {
                        removeBounceClass();
                    }, 1500);

                }, 2200);

            }
        }

    });

}

// **Calculate Minimum Swaps Using Graph Method**
function calculateMinSwapsUsingGraphMethod(solvedState) {
    // We need to wait until the DOM has fully rendered the grid items
    const items = Array.from(document.querySelectorAll('.grid-item'));
    const n = items.length;

    // Create an array of pairs where the first element is the current word
    // and the second element is the index it should be in the solved state
    const arrPos = [];
    for (let i = 0; i < n; i++) {
        arrPos.push([items[i].textContent, i]);
    }

    // Sort the array by the solved state's index
    arrPos.sort((a, b) => solvedState.indexOf(a[0]) - solvedState.indexOf(b[0]));

    // To keep track of visited elements, initialize all elements as not visited
    const vis = Array(n).fill(false);
    let ans = 0;

    // Traverse the array elements
    for (let i = 0; i < n; i++) {
        // Skip already visited elements or those in correct position
        if (vis[i] || arrPos[i][1] === i) {
            continue;
        }

        // Find out the number of nodes in this cycle and add to ans
        let cycleSize = 0;
        let j = i;
        while (!vis[j]) {
            vis[j] = true;
            // Move to next node
            j = arrPos[j][1];
            cycleSize++;
        }

        // Update answer by adding current cycle's swap count
        if (cycleSize > 0) {
            ans += cycleSize - 1;
        }
    }

    return ans;
}

// **Update Move Counter and Track Game State**
function updateMoveCounter(evt, originalPositions) {
    // Reset flags for this move
    columnCompletedThisMove = false;
    sentenceCompletedThisMove = false;

    const items = document.querySelectorAll('.grid-item');

    // Get the indices of moved items
    const fromIndex = evt.oldIndex;
    const toIndex = evt.newIndex;

    // Update positions and visual feedback
    checkCorrectPositionAtIndex(fromIndex, originalPositions);
    checkCorrectPositionAtIndex(toIndex, originalPositions);

    // Increment move counter
    moveCounterValue++;

    if (checkCorrectPositionAtIndex(fromIndex, originalPositions) == false && checkCorrectPositionAtIndex(toIndex, originalPositions) == false) {
        //increment X markers
        incorrectCounterValue++;
        const div = document.createElement('div');
        div.classList.add('error');
        div.innerHTML = '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4.10745 15.8925C3.67288 15.458 3.65698 14.7717 4.07189 14.3568L14.3568 4.07187C14.7717 3.65697 15.458 3.67286 15.8926 4.10743C16.3271 4.54201 16.343 5.22832 15.9281 5.64322L5.64324 15.9281C5.22833 16.343 4.54203 16.3271 4.10745 15.8925Z" fill="white"/><path d="M4.10745 4.10745C4.54203 3.67288 5.22833 3.65698 5.64324 4.07189L15.9281 14.3568C16.343 14.7717 16.3271 15.458 15.8926 15.8926C15.458 16.3271 14.7717 16.343 14.3568 15.9281L4.07189 5.64324C3.65699 5.22833 3.67288 4.54203 4.10745 4.10745Z"/></svg>'; //X svg icon
        errorCounterDisplay.append(div);

    }

    // Check for completed columns and sentences
    checkColumnCompletion(originalPositions);
    checkSentenceCompletion(originalPositions);
    checkPuzzleCompletion(originalPositions);

    // Update the move counter display
    updateMoveCounterDisplay();
}

// **Update Move Counter Display in Real-Time**
function updateMoveCounterDisplay() {
    if (moveCounterDisplay) {

        if (incorrectCounterValue > minimumMoves) {
            moveCounterDisplay.classList.add('over');
        }

        moveCounterDisplay.innerHTML = `<span>Moves</span> ${minimumMoves - incorrectCounterValue}`;

    }

    sentenceCounterDisplay.innerHTML = `<span>Sentences</span> ${completeSentenceCount}/${totalSentenceCount}`;
}

// **Check Individual Tile Position at Index**
function checkCorrectPositionAtIndex(index, originalPositions) {
    const items = document.querySelectorAll('.grid-item');
    const item = items[index];
    const isCorrect = item.textContent === originalPositions[index];

    if (isCorrect) {
        item.classList.add('correct-position');
    } else {
        item.classList.remove('correct-position');
        item.classList.remove('locked-position');
    }

    return isCorrect;
}


// **Check if a Column is Completed**
function checkColumnCompletion(originalPositions) {

    const items = document.querySelectorAll('.grid-item');
    const columns = 3; // Assuming a 3-column grid, adjust as necessary
    const rows = items.length / columns;
    const counters = document.querySelectorAll('.sentence-list-item');

    // Check each column to see if it's fully completed
    for (let col = 0; col < columns; col++) {
        if (completedColumns.includes(col)) continue;

        let isColumnCorrect = true;
        let gridItems = [];
        var sentenceCounter = '';

        for (let row = 0; row < rows; row++) {
            const index = row * columns + col;
            if (items[index].textContent !== originalPositions[index]) {
                isColumnCorrect = false;
            } else {
                sentenceCounter += originalPositions[index] + ' ';
                gridItems.push(index);
            }
        }

        if (isColumnCorrect) {
            completedColumns.push(col);
            completeSentenceCount++;
            animateWaveEffect(col, 'column');
            columnCompletedThisMove = true; // Set flag for this move

            let numLabel = completeSentenceCount;

            counters.forEach((item) => {
                item.classList.remove('active');
            });

            let thisCol = document.getElementById('sentence_' + completeSentenceCount);

            if (thisCol) {
                thisCol.classList.add('active');

                let contentEl = thisCol.querySelector('.content');

                if (contentEl) {
                    contentEl.innerHTML = completeSentenceCount + '. ' + sentenceCounter;
                    //contentEl.innerHTML = completeSentenceCount + '. ' + sentenceCounter + '(column)';
                }

                let gridCells = thisCol.querySelectorAll('.grid-cell');

                if (gridCells.length > 0) {
                    c = 0;
                    gridCells.forEach((cell) => {

                        if (gridItems.includes(c)) {
                            cell.classList.add('active');
                        }

                        c++;
                    });
                }

            }
        }
    }
}

// **Check if a Sentence is Completed**
function checkSentenceCompletion(originalPositions) {
    const items = document.querySelectorAll('.grid-item');
    const counters = document.querySelectorAll('.sentence-list-item');

    // Check each sentence to see if it's fully completed
    for (let index = 0; index < sentences.length; index++) {
        const [start, end] = sentences[index];
        let isSentenceCorrect = true;
        let sentenceCounter = '';
        let gridItems = [];

        //console.log('sentence start '+start+' sentence end '+end);

        if (completedSentences.includes(index)) continue;

        for (let i = start; i <= end; i++) {
            if (items[i].textContent !== originalPositions[i]) {
                isSentenceCorrect = false;
            } else {
                //if(items[i].classList.contains('')){
                sentenceCounter += originalPositions[i] + ' ';
                gridItems.push(i);
                //}
            }
        }

        //console.log('found tiles '+gridItems);

        if (isSentenceCorrect) {
            completedSentences.push(index);
            completeSentenceCount++;
            animateWaveEffect(index, 'sentence');
            sentenceCompletedThisMove = true; // Set flag for this move

            counters.forEach((item) => {
                item.classList.remove('active');
            });

            let thisRow = document.getElementById('sentence_' + completeSentenceCount);

            if (thisRow) {
                thisRow.classList.add('active');

                let contentEl = thisRow.querySelector('.content');

                if (contentEl) {
                    contentEl.innerHTML = completeSentenceCount + '. ' + sentenceCounter;
                    //contentEl.innerHTML = completeSentenceCount + '. ' + sentenceCounter +' (sentence)';
                }

                let gridCells = thisRow.querySelectorAll('.grid-cell');

                if (gridCells.length > 0) {
                    c = 0;
                    gridCells.forEach((cell) => {

                        if (gridItems.includes(c)) {
                            cell.classList.add('active');
                        }

                        c++;
                    });
                }

            }
        }
    }
}

// **Check if the Puzzle is Completed**
function checkPuzzleCompletion(originalPositions) {
    const items = document.querySelectorAll('.grid-item');
    const isSolved = Array.from(items).every(
        (item, index) => item.textContent === originalPositions[index]
    );

    let isFailed = 0;

    if (incorrectCounterValue > minimumMoves) {
        isFailed = 1;
    }

    if (isSolved) {
        animateWaveEffect(0, 'puzzle');
        startConfetti();
        document.getElementById('confetti-canvas').style.opacity = '1';
        showFinalScoreScreen();

        if (completedSessionPuzzles.includes(currentPuzzleID) === false) {
            completedSessionPuzzles.push(currentPuzzleID);
        }

        if (GameStorageService.getItem('ww-session-games')) {
            if (GameStorageService.getItem('ww-session-games').indexOf(completedSessionPuzzles) == -1) { //only update local storage if this puzzle ID isnt marked complete
                GameStorageService.setItem('ww-session-games', completedSessionPuzzles, 365);
            }
        } else {
            GameStorageService.setItem('ww-session-games', completedSessionPuzzles, 365);
        }

        setTimeout(() => {
            document.getElementById('confetti-canvas').style.opacity = '0';
            setTimeout(() => {
                stopConfetti();
            }, 8000);
        }, 8000);
    }

    if (isFailed) {
        // animateWaveEffect(0, 'puzzle');
        //startConfetti();
        //document.getElementById('confetti-canvas').style.opacity = '1';
        showFinalScoreScreen();

        /*

        if(completedSessionPuzzles.includes(currentPuzzleID) === false){
            completedSessionPuzzles.push(currentPuzzleID);
        }

        if(GameStorageService.getItem('ww-session-games')) {
            if(GameStorageService.getItem('ww-session-games').indexOf(completedSessionPuzzles) == -1 ) { //only update local storage if this puzzle ID isnt marked complete
                GameStorageService.setItem('ww-session-games',completedSessionPuzzles,365);
            }            
        } else {
            GameStorageService.setItem('ww-session-games',completedSessionPuzzles,365);
        }
        
        setTimeout(() => {
            document.getElementById('confetti-canvas').style.opacity = '0';
            setTimeout(() => {
                stopConfetti();
            },8000);
        },8000);
        */
    }
}

// **Animate the Wave Effect for Completed Sections**
function animateWaveEffect(index, type) {
    const items = document.querySelectorAll('.grid-item');
    let elements = [];

    // Determine elements based on type
    if (type === 'column') {
        for (let row = 0; row < items.length / 3; row++) {
            elements.push(items[row * 3 + index]);
        }
    } else if (type === 'sentence') {
        const [start, end] = sentences[index];
        for (let i = start; i <= end; i++) {
            elements.push(items[i]);
        }
    } else if (type === 'puzzle') {
        elements = Array.from(items);
    }

    // Apply the wave-bounce animation with staggered timing
    elements.forEach((element, i) => {
        setTimeout(() => {
            element.classList.add('wave-bounce');
            setTimeout(() => {
                element.classList.remove('wave-bounce');
                element.classList.add('completed');
            }, 600);
        }, i * 100);
    });
}

function finalScoreResizer(mode) {
    const pageSize = window.visualViewport ? window.visualViewport.height : window.innerHeight;
    const mainSection = document.querySelector('.main-section');
    const gameWrapper = document.querySelector('.game-wrapper');
    const finalPoemContent = document.querySelector('.final-poem');

    let mainHeight = pageSize - 76; // resize to page minus padding

    //console.log(mode);

    if (mode == 'results') {

        let reviewHeight = 550;

        // const finalPoemEntries = finalPoemContent.querySelectorAll('p');

        let addHeight = gameGridElement.offsetHeight;

        /*
        if(finalPoemEntries) {
            finalPoemEntries.forEach(item => {
                addHeight += item.offsetHeight;
            });
        }
        */
        finalPoemContent.style.display = 'block';

        reviewHeight = reviewHeight + parseInt(addHeight);

        if (mainHeight <= reviewHeight) {
            mainHeight = reviewHeight;
        }

        console.log('game height ' + mainHeight);

    } else {
        if (mainHeight <= 740) {
            mainHeight = 740;
        }

        finalPoemContent.style.display = 'none';
    }

    //only run on resize if final score screen is active
    if (finalScoreScreen.style.display == 'flex') {

        if (gameWrapper) {
            gameWrapper.style.height = `${mainHeight - 60}px`;
        }

        if (mainSection) {
            mainSection.style.height = `${mainHeight}px`;
        }

    }

    //console.log('final score game area height: '+mainHeight);

}

// **Show the Final Score Screen After Puzzle Completion**
function showFinalScoreScreen() {

    puzzleCounter++;

    setTimeout(() => {
        //gameScreen.style.display = 'none';
        finalScoreScreen.style.display = 'flex';
        finalScoreToggle.style.display = 'flex';
        finalMoveCount.innerHTML = `<span>Moves</span> ${moveCounterValue}`;
        moveCounterDisplay.style.display = "none";
        errorCounterDisplay.style.display = "none";
        sentenceCounterDisplay.style.display = "none";
        sentenceToggle[0].style.display = "none";
        sentenceToggle[1].style.display = "block";

        /* //removed this in place of showing final game board
        fullPoem.textContent = activePoem.fullPoem;
        poemColumn1.textContent = activePoem.columns[0];
        poemColumn2.textContent = activePoem.columns[1];
        poemColumn3.textContent = activePoem.columns[2];
        */

        const scoreTableElement = document.querySelector('.score-table');
        const scoreTableElements = document.querySelectorAll('.score-row');

        scoreTableElements.forEach(row => {

            let scoreFrom = row.dataset.from;
            let scoreTo = row.dataset.to;

            //console.log(moveCounterValue +'~'+ scoreFrom +'~'+ scoreTo);

            if (parseInt(moveCounterValue) >= parseInt(scoreFrom) && parseInt(moveCounterValue) <= parseInt(scoreTo)) {
                row.classList.add('highlighted');
                row.classList.add(row.id);
                finalMoveCount.classList.add(row.id);

                let rankIcon = row ? row.querySelector(".score-icon") : null;
                completedPuzzleIcon = rankIcon ? rankIcon.innerHTML.trim() : "";

                let rankTitle = row ? row.querySelector(".score-title") : null;
                completedPuzzleRank = rankTitle ? rankTitle.innerHTML.trim() : "";
            }
        });

        const toggles = document.querySelectorAll('.final-page-toggle .toggle');

        finalScoreResizer(toggled);

        toggles.forEach(toggle => {

            toggle.addEventListener('click', () => {

                toggles.forEach(toggle => {
                    toggle.classList.remove('active');
                });

                if (toggled == 'results') {
                    // finalPoem.style.display = 'none';
                    scoreTableElement.style.display = 'flex';
                    toggled = 'review';
                    toggles[0].classList.add('active');
                    gameGridElement.classList.remove('active');

                } else {
                    // finalPoem.style.display = 'flex';
                    scoreTableElement.style.display = 'none';
                    toggled = 'results';
                    toggles[1].classList.add('active');
                    gameGridElement.classList.add('active');
                }

                finalScoreResizer(toggled);

            });
        });

    }, 3000); // Delay to allow for wave animation to finish
}