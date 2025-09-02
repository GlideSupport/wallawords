// **GAME CONSTANTS**

// **DOM Element Selections**
const pageHeader = document.getElementById('header-section');
const titleScreen = document.getElementById('title-screen');
const gameScreen = document.getElementById('game-screen');
const finalScoreScreen = document.getElementById('final-score-screen');
const finalScoreToggle = document.getElementById('final-page-toggle');
const finalPoem = document.getElementById('final-poem'); 
const skinToggleButton = document.getElementById('skin-toggle-button');
const gameMenuButton = document.getElementById('game-menu-btn');
const finalMoveCount = document.getElementById('final-move-count');
const startButton = document.getElementById('start-button');
const playGameButton = document.getElementById('play-game');
const playAgain = document.getElementById('play-again');
const moveCounterDisplay = document.getElementById('move-counter'); // Move counter element
const sentenceCounterDisplay = document.getElementById('sentence-counter'); // Move counter element
const titleDisplay = document.getElementById('puzzle-title'); // Move counter element
const fullPoem = document.getElementById('full-poem'); // Move counter element
const poemColumn1 = document.getElementById('poem-column1'); // Move counter element
const poemColumn2 = document.getElementById('poem-column2'); // Move counter element
const poemColumn3 = document.getElementById('poem-column3'); // Move counter element

// **Game State Variables**
let currentPuzzleID = 0;
let moveCounterValue = 0; // Track the number of moves
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
let completedSessionPuzzles = []; //store in a cookie too?

let columnCompletedThisMove = false; // Flag for column completion
let sentenceCompletedThisMove = false; // Flag for sentence completion

document.addEventListener('DOMContentLoaded', initializeGame);

function initializeGame() {

    if(GameCookieService.getCookie('ww-skin-toggle')){
        var gameSkinSetting = GameCookieService.getCookie('ww-skin-toggle');
        document.body.classList.add(gameSkinSetting);
    } else {
        var gameSkinSetting = 'dark';
    }   

    console.log(gameSkinSetting);

// **Event Listeners for Navigation Buttons**

/*
gameMenuButton.addEventListener('click', () => {

    var gameMenu = document.getElementById('game-nav');
    
    if (gameMenu.classList.contains('active')) {
        gameMenu.style.display = 'none';
        gameMenu.classList.remove('active');
    } else {
        gameMenu.style.display = 'block';
        gameMenu.classList.add('active');
    }    
    
});

skinToggleButton.addEventListener('click', () => {
    if (document.body.classList.contains('light')) {
        document.body.classList.remove('light');
        GameCookieService.setCookie('ww-skin-toggle', 'dark', 2000);
    } else {
        document.body.classList.add('light');
        GameCookieService.setCookie('ww-skin-toggle', 'light', 2000);
    }    
});

startButton.addEventListener('click', () => {
    titleScreen.style.display = 'none';
    instructionScreen.style.display = 'flex';
    pageHeader.style.display = 'block';
    startInstructions(instructionSlide);
});
*/

playGameButton.addEventListener('click', () => {
    titleScreen.style.display = 'none';
    //gameScreen.style.display = 'flex';
    pageHeader.style.display = 'block';
    startGame(puzzleCounter);
});

playAgain.addEventListener('click', () => {
    instructionScreen.style.display = 'none';
    finalScoreScreen.style.display = 'none';
    document.getElementById('sortable-grid').innerHTML = '<span></span><img src="/wp-content/themes/wallawords/assets/build/images/spinner.svg" class="loading"><span></span>';
    startGame(puzzleCounter); 
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

// **Start the Game**
function startGame(puzzleCounterValue) {

    if(jQuery('#puzzle_id').length) {
        hasID = jQuery('#puzzle_id').val();
        url = '/wp-admin/admin-ajax.php?action=wallawords_get_puzzle_data&gameID='+hasID;
        currentPuzzleID = hasID;
    } else {
        url = '/wp-admin/admin-ajax.php?action=wallawords_get_puzzle_data';
    }

    if(completedSessionPuzzles){
        url += '&completed='+completedSessionPuzzles;
    }

    console.log(url);

    gameScreen.style.display = 'flex';

    //reset values on start
    moveCounterValue = 0;
    completeSentenceCount = 0;
    completedSentences = [];
    completedColumns = [];
    // Initialize move counter
    updateMoveCounterDisplay();

    if (gameGrid) gameGrid.destroy();

    jQuery.ajax({
        url: url,
        type: 'GET',
        success: function (response) {

            if(response){

            console.log(JSON.parse(response));

            const puzzles = JSON.parse(response);

            const originalDropPlacement = new Map(); // Map to store initial positions
    
            // Select a random puzzle
                const selectedPoem = puzzles[puzzleCounterValue];
                activePoem = selectedPoem;

                currentPuzzleID = selectedPoem.id; //poem ID
                title = selectedPoem.title; // Store the puzzle title
                originalPositions = selectedPoem.correctWords.slice(); // Store the original positions
                lockedWords = selectedPoem.lockedWords.slice(); // Store the original positions
                sentences = selectedPoem.sentences; // Store sentences from JSON
                columns = selectedPoem.columns; //store column rows
                totalSentenceCount = selectedPoem.sentences.length + 3;

                // Define the solved state explicitly
                const solvedState = originalPositions.slice(); // Assumes solved state is the initial state

                // Shuffle the remaining words using derangement
                const lockedIndexes = selectedPoem.lockedWords;
                const incorrectWords = selectedPoem.incorrectWords;

                titleDisplay.textContent = selectedPoem.title;

                // Populate the grid with words
                const grid = document.getElementById('sortable-grid');
                grid.innerHTML = ''; // Clear previous words

                originalPositions.forEach((word, index) => {
                    const div = document.createElement('div');
                    div.classList.add('grid-item');                
                    if (lockedIndexes.includes(index)) {
                        //console.log('this is locked');
                        div.textContent = word;
                        div.classList.add('correct-position'); // Mark it as correctly placed
                        div.classList.add('locked-position'); // Mark it as locked
                    } else {
                        div.textContent = incorrectWords.shift(); // Populate with shuffled words
                    }
                    grid.appendChild(div);
                });

                //get original placements as an array object to compare
                document.querySelectorAll('.grid-item').forEach((tile) => {
                    const parent = tile.parentElement;
                    const index = Array.from(parent.children).indexOf(tile);
                    originalDropPlacement.set(tile, { parent, index });
                    let rand = Math.round(Math.random() * (2 - 1) + 1);
                    tile.classList.add('intro'+rand);             
                });
                                    
                // Calculate the minimum number of moves required using cycle decomposition method
                minimumMoves = calculateMinSwapsUsingGraphMethod(solvedState);
                // console.log(`Lowest possible is ${minimumMoves} moves`); // Log minimum moves to console

                // Initialize SortableJS on the grid container with Swap plugin
                gameGrid = new Sortable(grid, {
                    animation: 150,
                    ghostClass: 'sortable-ghost',
                    chosenClass: 'dragging',
                    dragClass: 'sortable-drag',
                    fallbackOnBody: true,
                    forceFallback: true,
                    swap: true,
                    swapClass: 'sortable-swap-highlight',
                    // Called by any change to the list (add / update / remove)
                    onSort: function (evt) {
                        updateMoveCounter(evt, originalPositions);
                        // Remove unnecessary classes to ensure draggable functionality
                        removeDragClasses();

                    },
                    onMove: function (evt) {
                        removeDropZoneClass();
                        if (evt.related && !evt.related.classList.contains('locked-position')) {
                            evt.related.classList.add('drop-zone');
                        }

                    },
                    onUnchoose: function (evt) {
                        removeDropZoneClass();
                        //console.log('unchosen');
                    },
                    onEnd: function (evt) {
                        const item = evt.item;
                        const { parent, index } = originalDropPlacement.get(item);

                        // Check if the item was placed in an invalid slot
                        // if (!item.parentElement.classList.contains('valid-slot')) {
                            //parent.insertBefore(item, parent.children[index]); // Revert to original position
                        //}
                        removeDropZoneClass();
                    }
                    
                });
           
                setTimeout(()=> {
                    document.querySelectorAll('.grid-item').forEach((tile) => {
                        tile.classList.remove('intro1', 'intro2');
                    });
                },1000);             

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

    // Increment move counter
    moveCounterValue++;

    const items = document.querySelectorAll('.grid-item');

    // Get the indices of moved items
    const fromIndex = evt.oldIndex;
    const toIndex = evt.newIndex;

    // Update positions and visual feedback
    checkCorrectPositionAtIndex(fromIndex, originalPositions);
    checkCorrectPositionAtIndex(toIndex, originalPositions);

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
        moveCounterDisplay.innerHTML = `<span>Moves</span> ${moveCounterValue}`; // Display as #10, etc.
    }

    // if (completeSentenceCount) {
        sentenceCounterDisplay.innerHTML = `<span>Sentences</span> ${completeSentenceCount}/${totalSentenceCount}`; // Display as #10, etc.
    // }
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

    // Check each column to see if it's fully completed
    for (let col = 0; col < columns; col++) {
        if (completedColumns.includes(col)) continue;

        let isColumnCorrect = true;

        for (let row = 0; row < rows; row++) {
            const index = row * columns + col;
            if (items[index].textContent !== originalPositions[index]) {
                isColumnCorrect = false;
                // break;
            }
        }

        if (isColumnCorrect) {
            completedColumns.push(col);
            completeSentenceCount ++;
            animateWaveEffect(col, 'column');
            columnCompletedThisMove = true; // Set flag for this move
            // break;
        }
    }
}

// **Check if a Sentence is Completed**
function checkSentenceCompletion(originalPositions) {
    const items = document.querySelectorAll('.grid-item');

    // Check each sentence to see if it's fully completed
    for (let index = 0; index < sentences.length; index++) {
        const [start, end] = sentences[index];
        let isSentenceCorrect = true;
        if (completedSentences.includes(index)) continue;


        for (let i = start; i <= end; i++) {
            if (items[i].textContent !== originalPositions[i]) {
                isSentenceCorrect = false;
                // break;
            }
        }

        if (isSentenceCorrect) {
            completedSentences.push(index);
            completeSentenceCount ++;
            animateWaveEffect(index, 'sentence');
            sentenceCompletedThisMove = true; // Set flag for this move
            // break;
        }
    }
}

// **Check if the Puzzle is Completed**
function checkPuzzleCompletion(originalPositions) {
    const items = document.querySelectorAll('.grid-item');
    const isSolved = Array.from(items).every(
        (item, index) => item.textContent === originalPositions[index]
    );

    if (isSolved) {
        animateWaveEffect(0, 'puzzle');
        startConfetti();
        document.getElementById('confetti-canvas').style.opacity = '1';
        showFinalScoreScreen();
        completedSessionPuzzles.push(currentPuzzleID);
        setTimeout(() => {
            document.getElementById('confetti-canvas').style.opacity = '0';
            setTimeout(() => {
                stopConfetti();
            },8000);
        },8000);
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
            }, 600);
        }, i * 100);
    });
}

// **Show the Final Score Screen After Puzzle Completion**
function showFinalScoreScreen() {
    puzzleCounter++;
    setTimeout(() => {
        gameScreen.style.display = 'none';
        finalScoreScreen.style.display = 'flex';
        finalScoreToggle.style.display = 'flex';
        finalMoveCount.innerHTML = `<span>moves</span> ${moveCounterValue}`;

        fullPoem.textContent = activePoem.fullPoem;
        poemColumn1.textContent = activePoem.columns[0];
        poemColumn2.textContent = activePoem.columns[1];
        poemColumn3.textContent = activePoem.columns[2];

        const scoreTableElement = document.querySelector('.score-table');
        const scoreTableElements = document.querySelectorAll('.score-row');
        
        scoreTableElements.forEach(row => {

            let scoreFrom = row.dataset.from;
            let scoreTo = row.dataset.to;

            console.log(moveCounterValue +'~'+ scoreFrom +'~'+ scoreTo);

            if(parseInt(moveCounterValue) >= parseInt(scoreFrom) && parseInt(moveCounterValue) <= parseInt(scoreTo)) {
                row.classList.add('highlighted');
                row.classList.add(row.id);
                finalMoveCount.classList.add(row.id);
            }
        });
        /*
        // Define score categories and their corresponding move ranges
        const scoreTable = [
            { label: 'master', range: `${minimumMoves} moves` },
            { label: 'ninja', range: `${minimumMoves + 1}-${minimumMoves + 3} moves` },
            { label: 'apprentice', range: `${minimumMoves + 4}-${minimumMoves + 6} moves` },
            { label: 'wanderer', range: `${minimumMoves + 7}+ moves` }
        ];

        // Clear existing score table entries
        scoreTableElement.innerHTML = '';

        // Populate the score table and highlight the appropriate category
        scoreTable.forEach(score => {
            const row = document.createElement('div');
            row.classList.add('score-row');

            const labelCell = document.createElement('span');
            labelCell.classList.add('score-title');
            labelCell.textContent = score.label;

            const rangeCell = document.createElement('span');
            rangeCell.classList.add('score-moves');
            rangeCell.textContent = score.range;

            row.appendChild(labelCell);
            row.appendChild(rangeCell);

            if (score.label === getScoreCategory()) {
                row.classList.add('highlighted');
                row.classList.add(getScoreCategory());
                finalMoveCount.classList.add(getScoreCategory());
            }

            scoreTableElement.appendChild(row);

            */

            const toggles = document.querySelectorAll('.final-page-toggle .toggle');
            var toggled = 'review';

            toggles.forEach(toggle => {
                
                toggle.addEventListener('click', () => {

                    toggles.forEach(toggle => {
                        toggle.classList.remove('active');
                    });

                    if(toggled == 'results'){
                        finalPoem.style.display = 'none';
                        scoreTableElement.style.display = 'flex';
                        toggled = 'review';
                        toggles[0].classList.add('active');
                    } else {
                        finalPoem.style.display = 'flex';
                        scoreTableElement.style.display = 'none';
                        toggled = 'results';
                        toggles[1].classList.add('active');
                    }                
                });
            });
                                
    }, 3000); // Delay to allow for wave animation to finish
}

// **Determine the Score Category Based on Move Count**
function getScoreCategory() {
    if (moveCounterValue === minimumMoves) {
        return 'master';
    } else if (moveCounterValue <= minimumMoves + 3) {
        return 'ninja';
    } else if (moveCounterValue <= minimumMoves + 6) {
        return 'apprentice';
    } else {
        return 'wanderer';
    }
}

const GameCookieService = {

    setCookie(name, value, days) {
        let expires = '';

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