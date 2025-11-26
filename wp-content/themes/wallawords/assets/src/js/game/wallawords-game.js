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

document.addEventListener("DOMContentLoaded", function () {
    if (localStorage.getItem("puzzle-acadamy-level")) {
        GameStorageService.setItem('puzzle-acadamy-level', 1, 1);
    }
});
const showAcademyPuzzlePopupButton = document.getElementById('show-academy-puzzle-popup');
showAcademyPuzzlePopupButton.addEventListener('click', () => {
    console.log('showAcademyPuzzlePopupButton clicked');
    if (localStorage.getItem("puzzle-acadamy-level")) {
        GameStorageService.setItem('puzzle-acadamy-level', 1, 1);
    }
    document.getElementById('final-puzzle-acadamy-popup').classList.remove('active');
    document.getElementById('start-puzzle-acadamy-popup').style.display = 'flex';
    document.getElementById('difficulty-popup').style.display = 'none';
    
});  



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
const gameRow = document.getElementById('game-row');
const gameGridElement = document.getElementById('sortable-grid');
const gamePrompt = document.getElementById('game-prompt');
const finalScoreScreen = document.getElementById('final-score-screen');
const finalAcadamyPuzzlePopup = document.getElementById('final-puzzle-acadamy-popup');
// const finalScoreToggle = document.getElementById('final-page-toggle');
const finalPoem = document.getElementById('final-poem');
const skinToggleButton = document.getElementById('skin-toggle-button');
const gameMenuButton = document.getElementById('game-menu-btn');
const finalMoveCount = document.getElementById('final-move-count');
const startButton = document.getElementById('start-button');
const playGameButton = document.getElementById('play-game');
const playAgain = document.getElementById('play-again');
const backToPuzzle = document.getElementById('back-to-puzzle');
const backToResult = document.getElementById('back-to-result');
const nextLevel = document.getElementById('next-level');
const levelDone = document.getElementById('level-done');
const levelBadge = document.getElementById('level-badge');
const shareBtn = document.getElementById('share-button');
const shareBtnSend = document.getElementById('share-send');
const shareBtnClose = document.getElementById('share-close');
const shareModal = document.getElementById('share-modal');
const errorCounterDisplay = document.getElementById('error-counter'); // Move counter element
const moveCounterDisplay = document.getElementById('move-counter'); // Move counter element
const sentenceCounterDisplay = document.getElementById('sentence-counter'); // Move counter element
const resultSentenceCounterDisplay = document.getElementById('result-sentence-counter'); 
const finalScoreSentence = document.querySelector('#final-score-screen ol');
//const sentenceToggle = document.getElementById('sentence-toggle');
const sentenceToggle = document.querySelectorAll('.sentence-toggle');
const closeSentenceToggle = document.getElementById('close-sentences-button');
const kicker = document.getElementById('kicker');
const titleDisplay = document.getElementById('puzzle-title'); // Move counter element
const fullPoem = document.getElementById('full-poem'); // Move counter element
const poemColumn1 = document.getElementById('poem-column1'); // Move counter element
const poemColumn2 = document.getElementById('poem-column2'); // Move counter element
const poemColumn3 = document.getElementById('poem-column3'); // Move counter element
const difficultyPopup = document.getElementById('difficulty-popup'); // Move counter element
const difficultyPlayGameButton = document.getElementById('difficulty-play-game');

const gameLevelIcon = document.querySelector('.header-wrapper .game-level-icon');

// **Game State Variables**
let currentPuzzleID = 0;
let lastPuzzleID = 0;
const totalTiles = 15;
let moveCounterValue = 0; // Track the number of total moves
let health = 7; // Track the number of health moves
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
let difficultySessionPuzzles = [];

let isSolved = 0;
let isFailed = 0;
if (GameStorageService.getItem('ww-session-games')) {
    completedSessionPuzzles = GameStorageService.getItem('ww-session-games');
}
if (GameStorageService.getItem('difficultySessionPuzzles')) {
    difficultySessionPuzzles = GameStorageService.getItem('difficultySessionPuzzles');
}

let completedPuzzleRank = '';
let completedPuzzleIcon = '';
let isFirstPlay = 1;
let toggled = 'review';

let columnCompletedThisMove = false; // Flag for column completion
let sentenceCompletedThisMove = false; // Flag for sentence completion
let ispuzzleAcadamy = false;
let puzzleAcadamyLevel = 1;
if (GameStorageService.getItem('puzzle-acadamy-level')) {
    puzzleAcadamyLevel = GameStorageService.getItem('puzzle-acadamy-level');
}else{
    GameStorageService.setItem('puzzle-acadamy-level', puzzleAcadamyLevel, 1);
}
document.addEventListener('DOMContentLoaded', initializeGame);

function initializeGame() {

  // header icon 
    document.addEventListener('mouseenter', function(e) {

        const el = e.target.closest('.custom-tt-btn');
        if (!(e.target instanceof Element)) return; // FIX
        classList.add('hover-tooltip');
        if (!el) return;

        const tooltipContent = el.querySelector('.custom-tooltip-content');
        if (tooltipContent && tooltipContent.textContent.trim() !== '') {
            el.classList.add('show-tooltip');
            document.body.classList.add('activated-tooltip');
        }
    }, true);


    document.addEventListener('mouseleave', function(e) {

        const el = e.target.closest('.custom-tt-btn');
        if (!(e.target instanceof Element)) return; // FIX
        classList.remove('hover-tooltip');
        if (!el) return;

        const tooltipContent = el.querySelector('.custom-tooltip-content');
        if (tooltipContent && tooltipContent.textContent.trim() !== '') {
            el.classList.remove('show-tooltip');
            document.body.classList.remove('activated-tooltip');
        }
    }, true);


    // header icon 

    // Difficulty popup tab click handling
    const tabs = difficultyPopup.querySelectorAll('.tab');

    tabs.forEach(tab => {
        tab.addEventListener('click', function() {

            // Optionally, add active class
            tabs.forEach(t => t.classList.remove('active')); // remove active from all
            this.classList.add('active'); // add active to clicked tab
            const difficulty = this.getAttribute('data-difficulty');
            const levelNumber = this.getAttribute('data-levelnumber');
            const icon = this.querySelector('img').getAttribute('src');
            const message = this.getAttribute('data-message');
            const message_tootip = this.getAttribute('data-message_tootip');
            if(icon){
                this.closest('#difficulty-popup').querySelector('.badge-box .hexagone-icon .icon img').setAttribute('src', icon);
                const badge = this.closest('#difficulty-popup').querySelector('#level-badge');
                // Remove existing level classes
                badge.classList.remove('level-1', 'level-2', 'level-3');

                // Add the new level class
                badge.classList.add('level-' + levelNumber);
            }
            if(message){
                const badge = this.closest('#difficulty-popup').querySelector('.level-content');
                badge.innerHTML = message;
            }
            if(message_tootip){
                const tootipbadge = document.querySelector('.game-level-icon .custom-tooltip-content');
                tootipbadge.innerHTML = message_tootip;
            }

        });
    });

    difficultyPlayGameButton.addEventListener('click', () => {
        difficultyPopup.style.display = 'block';
        difficultyPopup.querySelector('.level-tabs .tab-1').click();
    });
    
    document.querySelector('#header-difficulty-play-game').addEventListener('click', (e) => {
        difficultyPopup.style.display = 'flex';
        difficultyPopup.querySelector('.level-tabs .tab-1').click();
        if (e.target.closest('#instruction-screen')) {
            document.querySelector('#instruction-screen .header .close-button').click();
        }
    });

    // Difficulty popup tab click handling
    
    if (GameStorageService.getItem('ww-played-before')) {
        isFirstPlay = 0;
    } else {
        //GameCookieService.setCookie('ww-played-before',1,365);
        GameStorageService.setItem('ww-played-before', 1, 1);
    }
    playGameButton.addEventListener('click', (e) => {
        // if (isFirstPlay == 1) {
        //     jQuery('#help-button').trigger('click');
        // }
        gameGridElement.innerHTML = `<span></span><img src="${localVars.site_url}/wp-content/themes/wallawords/assets/src/images/spinner.svg" class="loading"><span></span>`;
        titleScreen.style.display = 'none';
        difficultyPopup.style.display = 'none';
        //gameScreen.style.display = 'flex';
        const popup = e.target.closest('#difficulty-popup');
        if (popup) {
            popup.classList.add('active-difficulty-level-game');
        }   
        
        const difficulty_level = document.querySelector('#difficulty-popup .tab.active').getAttribute('data-level');
        const icon = document.querySelector('#difficulty-popup .tab.active img').getAttribute('src');
        gameLevelIcon.classList.add(difficulty_level);
        gameLevelIcon.querySelector('img').setAttribute('src', icon);
        gameLevelIcon.style.display= 'block';

        startGame(puzzleCounter);
    });
    if(playAgain){
        playAgain.addEventListener('click', () => {
            instructionScreen.style.display = 'none';
            finalScoreScreen.style.display = 'none';
            gameGridElement.innerHTML = `<span></span><img src="${localVars.site_url}/wp-content/themes/wallawords/assets/src/images/spinner.svg" class="loading"><span></span>`;
            startGame(puzzleCounter);
        });
    }

    if(nextLevel){
        nextLevel.addEventListener('click', () => {
            
            puzzleAcadamyLevel = GameStorageService.getItem('puzzle-acadamy-level');
            console.log('nextLevel clicked, puzzleAcadamyLevel:', puzzleAcadamyLevel);
            if( puzzleAcadamyLevel == 'done' ){
                //startGame(puzzleCounter);
                document.getElementById('final-puzzle-acadamy-popup').style.display = 'none';
                document.getElementById('difficulty-popup').style.display = 'block';
            }else{
                document.body.classList.remove('final-acadamy-result');
                finalAcadamyPuzzlePopup.classList.remove('active');
                finalAcadamyPuzzlePopup.style.display = 'none';
                gameGridElement.innerHTML = `<span></span><img src="${localVars.site_url}/wp-content/themes/wallawords/assets/src/images/spinner.svg" class="loading"><span></span>`;
                startGame(puzzleAcadamyLevel, true);
            }
        });
    }
    if(backToPuzzle){
        backToPuzzle.addEventListener('click', () => {
            document.body.classList.remove('final-acadamy-result', 'final-result');
            finalScoreScreen.style.display = 'none';
            document.body.style.overflow = 'auto';
            titleDisplay.style.display = 'none';
            if(finalScoreSentence.querySelectorAll('li').length > 0){
             document.querySelectorAll('.grid-item').forEach((item) => {
                item.classList.remove('dimmed');
             });
            }

            moveCounterDisplay.innerHTML = `Health`;
            resultSentenceCounterDisplay.style.display = 'none';
            sentenceCounterDisplay.removeAttribute('style');
            gameRow.removeAttribute('style');
            backToResult.removeAttribute('style');
        });
    }
     if(backToResult){
        backToResult.addEventListener('click', () => {
            document.body.classList.add('final-result');
            finalScoreScreen.removeAttribute('style');
            titleDisplay.removeAttribute('style');
            moveCounterDisplay.innerHTML = `Health:`;
            resultSentenceCounterDisplay.removeAttribute('style');
            sentenceCounterDisplay.style.display = 'none';
            gameRow.style.display = 'none';
        });
    }
    
    if(shareBtn){
        shareBtn.addEventListener('click', () => {

            let message = `Check out my latest Walla score! I solved it with “${health}” health left 🕵. Think you can beat me? Try it here - https://wallawords.com/play`;

            if (document.querySelector('#final-score-screen').classList.contains('final-result-faild')) {
                const difficulty = difficultyPopup
                    .querySelector('.level-tabs .active')
                    .getAttribute('data-level');

                message = `I couldn’t solve the Walla on ${difficulty} today, but maybe you can! Try it here - https://wallawords.com/play`;
            }

            let smsLink = `sms:?&body=${encodeURIComponent(message)}`;
            window.location.href = smsLink;
        });
    }
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
            // sentenceToggle[1].style.display = "block";
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
function startGame(puzzleCounterValue, isAcadamy = false) {
    const difficulty = difficultyPopup.querySelector('.level-tabs .active').getAttribute('data-level');
    pageHeader.classList.add('playing');
    moveCounterDisplay.classList.remove('over');
    finalScoreScreen.style.display = 'none';
    moveCounterDisplay.style.display = "block"; 
    sentenceCounterDisplay.style.display = "block";
    backToResult.style.display = 'none';
    // errorCounterDisplay.style.display = "block";
    // errorCounterDisplay.innerHTML = '';
    ispuzzleAcadamy = isAcadamy;
    if(isAcadamy){
        url = localVars.ajax_url + '?action=wallawords_get_puzzle_data&is_acadamy=' + isAcadamy + '&nonce=' + localVars.nonce;
        puzzleAcadamyLevel = GameStorageService.getItem('puzzle-acadamy-level');
        if(puzzleAcadamyLevel){
            url += '&level=' + puzzleAcadamyLevel;
        }
    }else{
        console.log('normal puzzle load');
        if (jQuery('#puzzle_id').length) {
            console.log('with puzzle_id true');
            console.log('with puzzle_id');
            hasID = jQuery('#puzzle_id').val();
            url = localVars.ajax_url + '?action=wallawords_get_puzzle_data&gameID=' + hasID + '&nonce=' + localVars.nonce;
            currentPuzzleID = hasID;
        } else {
            console.log('with puzzle_id false');
            url = localVars.ajax_url + '?action=wallawords_get_puzzle_data&difficulty='+difficulty+'&nonce=' + localVars.nonce;
        }
        if (completedSessionPuzzles) {
            if (GameStorageService.getItem('difficultySessionPuzzles'+`${completedSessionPuzzles}`) && GameStorageService.getItem('difficultySessionPuzzles'+`${completedSessionPuzzles}`).includes(difficulty) ) {
                console.log('already played this difficulty');
                url += '&completed=' + completedSessionPuzzles;
            }
        }
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('preview') === 'true') {
            url += '&preview=true';
        }
    }
    gameScreen.style.display = 'flex';
    const floatingPieces = document.querySelectorAll('.piece');
    floatingPieces.forEach((piece) => {
        piece.classList.add('blur'); //change floating elements
    });
    //reset values on start
    moveCounterValue = 0;
    completeSentenceCount = 0;
    incorrectCounterValue = 0;
    isFailed = 0;
    completedSentences = [];
    completedColumns = [];
    console.log('destroying previous sortable instance', gameGrid);
    if (gameGrid) {
        gameGrid.destroy();
        const resetSentenceCounter = document.querySelectorAll('.sentence-item');
        resetSentenceCounter.forEach((row) => {
            row.remove();
        });
    }
    jQuery.ajax({
        url: url,
        type: 'GET',
        success: async function (response) {
           
            let data = response.data;
            if (response.success === 'true') {
                
                try {
                    data = JSON.parse(response.data);
                } catch (err) {
                    console.error("JSON.parse failed:", err, response.data);
                    return;
                }
            }
            if (data) {
                
                try {
                    await dpData(data, localVars.nonce);
                } catch (err) {
                    console.error("Puzzle failed:", err);
                }
                const puzzles = await dpData(data, localVars.nonce);
                sentenceToggle.forEach(item => {
                    item.style.display = "block";
                });
                document.body.classList.add('steps-page');
                const originalDropPlacement = new Map();
                puzzleCounterValue = puzzles.length > puzzleCounterValue ? puzzleCounterValue : puzzles.length - 1;
                var selectedPoem = puzzles[puzzleCounterValue];
                activePoem = selectedPoem;
                currentPuzzleID = selectedPoem.id; //poem ID 
                var playStatus = GameStorageService.getItem(`puzzleStatus${currentPuzzleID}`);
                if(playStatus != null && !ispuzzleAcadamy){
                    gameGridElement.innerHTML = `<span></span><img src="${localVars.site_url}/wp-content/themes/wallawords/assets/src/images/spinner.svg" class="loading"><span></span>`;
                    gameRow.style.display = 'none';
                }
                // if(playStatus !== 'null' || playStatus !== 'undefine')
                title = selectedPoem.title; // Store the puzzle title

                console.log("Selected poem title:", selectedPoem.fullPoem);
                document.querySelector('#main-section').setAttribute('data-puzzle-title', selectedPoem.fullPoem);
                document.querySelector('#main-section').setAttribute('data-puzzle-id', selectedPoem.id);

                originalPositions = selectedPoem.correctWords.slice(); // Store the original positions
                // console.log(selectedPoem.correctWords);
                lockedWords = selectedPoem.lockedWords.slice(); // Store the locked positions
                sentences = selectedPoem.sentences; // Store sentences from JSON
                columns = selectedPoem.columns; //store column rows
                totalSentenceCount = selectedPoem.sentences.length + 3; //add 3 because we always have 3 vertical sentences in a puzzle
                const solvedState = originalPositions.slice(); // Assumes solved state is the initial state
                // Shuffle the remaining words using derangement
                const lockedIndexes = selectedPoem.lockedWords;
                const incorrectWords = selectedPoem.incorrectWords;
                titleDisplay.textContent = selectedPoem.title;
                gamePrompt.innerHTML = selectedPoem.prompt;
                gameGridElement.innerHTML = ''; // Clear previous words
                health = selectedPoem.health;
                originalPositions.forEach((word, index) => {
                    
                    const div = document.createElement('div');
                    
                    div.classList.add('grid-item');
                    if (lockedIndexes.includes(index)) {
                        div.textContent = word;
                        div.classList.add('correct-position'); // Mark it as correctly placed
                        div.classList.add('locked-position'); // Mark it as locked
                        div.classList.add('intro1');
                        // div.style.animationDelay = `${parseInt(index) * .01}s`; //add delay to float in effect to stagger tiles  
                    } else {
                        div.textContent = incorrectWords.shift(); // Populate with shuffled words
                        div.classList.add('intro2');
                        div.classList.add('start-position');
                        var rand = parseInt(Math.floor(Math.random() * (originalPositions.length - 0 + 1) + 0));
                        var randDelay = (rand * .01) + ((parseInt(index) * .01) * lockedIndexes.length);
                        //console.log(randDelay);
                        // div.style.animationDelay = `${randDelay}s`; //add delay to float in effect to stagger tiles                      
                    }
                    gameGridElement.appendChild(div);
                });
                //get original placements as an array object to compare
                
                let lockcount = 0;
                let normalcount = 0;

                document.querySelectorAll('.grid-item').forEach((tile) => {

                    // Remove spaces and line breaks
                    let clean = tile.textContent.replace(/\s+/g, '');

                    // Add min-nine-characters class
                    if (clean.length > 8) {
                        tile.classList.add('min-nine-characters');
                    }

                    // Locked / normal delay handling
                    if (tile.classList.contains('locked-position')) {
                        lockcount++;
                        tile.style.animationDelay = `${lockcount * 0.01}s`;
                    } else {
                        
                        normalcount++;
                        console.log(normalcount);
                        tile.style.animationDelay = `${(normalcount / 10) + 0.25}s`;
                    }

                    // Save original placement (requires Map)
                    const parent = tile.parentElement;
                    const index = Array.from(parent.children).indexOf(tile);

                    originalDropPlacement.set(tile, { parent, index });
                });

                // Calculate the minimum number of moves required using cycle decomposition method
                minimumMoves = calculateMinSwapsUsingGraphMethod(solvedState);
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
                        // updateMoveCounter(evt, originalPositions);
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
                        if (evt.newIndex === undefined || evt.oldIndex === evt.newIndex) return;
                        let targetTile = evt.from.children[evt.newIndex];
                        if (targetTile && targetTile.classList.contains("locked-position")) return;
                        const domIndex = Array.from(gameGridElement.children).indexOf(item);
                        if (item.textContent.trim() !== originalPositions[domIndex]) {
                            // Delay shake/pulse-red until after Sortable animation (150ms)
                            incorrectCounterValue++;
                            setTimeout(() => {
                                item.classList.add('warning', 'zingle', 'nohover');
                                item.addEventListener('animationend', function handler(e) {
                                    if (e.animationName === 'shake') {
                                        item.classList.remove('zingle');
                                    }
                                    if (e.animationName === 'pulseRed') {
                                        item.classList.remove('warning');
                                    }
                                    if (!item.classList.contains('zingle') &&  !item.classList.contains('warning')) {
                                        setTimeout(() => item.classList.remove('nohover'), 50);
                                        item.removeEventListener('animationend', handler);
                                    }
                                });
                            }, 150);
                        }else {
                            item.dataset.justPlaced = "true";
                             setTimeout(() => {
                            // Remove any previous animationend handler
                                item.removeEventListener('animationend', item._bounceHandler);
                                // Remove bounce if present, force reflow, then add bounce
                                item.classList.remove('bounce');
                                void item.offsetWidth;
                                item.classList.add('bounce',  'nohover');
                                // Define and store the handler so it can be removed next time
                                item._bounceHandler = function handler(e) {
                                    if (e.animationName === 'tileBounce') {
                                    item.classList.remove('bounce');
                                    setTimeout(() => item.classList.remove('nohover'), 50);
                                    item.removeEventListener('animationend', handler);
                                    }
                                };
                                item.addEventListener('animationend', item._bounceHandler);
                            }, 150);
                        }
                        if (incorrectCounterValue >= health) {
                            let gitems = document.querySelectorAll('.grid-item');
                            gitems.forEach(item => {
                                item.classList.add('noHover');
                            });
                        }
                        updateMoveCounter(evt, originalPositions);
                        removeDropZoneClass();
                    }
                });
                
                /* counter grids */
                //set default sentence counters
                for (s = 1; s <= totalSentenceCount; s++) {
                    const li = document.createElement('li');
                    li.id = 'sentence_' + s;
                    li.classList.add('sentence-item');
                    li.innerHTML = '';
                    finalScoreSentence.appendChild(li);
                }
                // 
                var playStatus = GameStorageService.getItem(`puzzleStatus${currentPuzzleID}`);
                if(playStatus != null && !ispuzzleAcadamy){
                    if(!playStatus){
                        isFailed = 1;
                    }
                    incorrectCounterValue = GameStorageService.getItem(`helth${currentPuzzleID}`, );
                    updateMoveCounterDisplay();
                    showFinalScoreScreen();
                    
                }else{
                     console.log('working not');

                     // TODO
                     let foundKey = null;

                    for (let i = 0; i < localStorage.length; i++) {
                        let key = localStorage.key(i);

                        if (key.startsWith("puzzleStatus")) {
                            foundKey = key;
                            break;
                        }
                    }

                    console.log("Matched Key:", foundKey);
                    //remove intro animation classes
                    updateMoveCounterDisplay();
                    
                    setTimeout(() => {
                        removeAnimateInClass();
                        setTimeout(() => {
                            removeBounceClass();
                             document.body.classList.remove('final-acadamy-result', 'final-result');
                             gameRow.style.display = 'block';
                             titleDisplay.style.display = 'none';
                        }, 1500);
                    }, 2200);
                }
                
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
        // incorrectCounterValue++;
        
        // const div = document.createElement('div');
        // div.classList.add('error');
        // div.innerHTML = '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4.10745 15.8925C3.67288 15.458 3.65698 14.7717 4.07189 14.3568L14.3568 4.07187C14.7717 3.65697 15.458 3.67286 15.8926 4.10743C16.3271 4.54201 16.343 5.22832 15.9281 5.64322L5.64324 15.9281C5.22833 16.343 4.54203 16.3271 4.10745 15.8925Z" fill="white"/><path d="M4.10745 4.10745C4.54203 3.67288 5.22833 3.65698 5.64324 4.07189L15.9281 14.3568C16.343 14.7717 16.3271 15.458 15.8926 15.8926C15.458 16.3271 14.7717 16.343 14.3568 15.9281L4.07189 5.64324C3.65699 5.22833 3.67288 4.54203 4.10745 4.10745Z"/></svg>'; //X svg icon
        // errorCounterDisplay.append(div);
    }
    // Check for completed columns and sentences
    checkColumnCompletion(originalPositions);
    checkSentenceCompletion(originalPositions);
    checkPuzzleHelth(originalPositions);
    // Update the move counter display
    updateMoveCounterDisplay();
}

// **Update Move Counter Display in Real-Time**
function updateMoveCounterDisplay() {
    if (moveCounterDisplay) {
        if (incorrectCounterValue > minimumMoves) {
            moveCounterDisplay.classList.add('over');
        }
        moveCounterDisplay.innerHTML = `Health`;//<span>Moves</span> ${minimumMoves - incorrectCounterValue}  
        // var health = minimumMoves - incorrectCounterValue;
        var currentHealth = health - incorrectCounterValue;
        if(ispuzzleAcadamy){
            puzzleAcadamyLevel = GameStorageService.getItem('puzzle-acadamy-level');
            moveCounterDisplay.innerHTML = `Health: <span>${currentHealth}<span>`;
            sentenceCounterDisplay.innerHTML = `Level: <span>${puzzleAcadamyLevel}/3</span>`;
        }else{
            sentenceCounterDisplay.innerHTML = `<span>${currentHealth}</span>`;
            resultSentenceCounterDisplay.innerHTML = `<span>${currentHealth}</span>`;
        }
        var healthBar = '';
        for (let i = 1; i <= health; i++) {
            if (i <= currentHealth) {
                healthBar += `<div class="segment active"></div>`;
            } else {
                healthBar += `<div class="segment"></div>`;
            }
        }
        const healthBarSelctor = document.getElementById('health-bar');
        if (healthBarSelctor) {
            healthBarSelctor.innerHTML = healthBar;
            healthBarSelctor.removeAttribute('style');
        }
    }
    // sentenceCounterDisplay.innerHTML = `<span>Sentences</span> ${completeSentenceCount}/${totalSentenceCount}`;
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
    const counters = document.querySelectorAll('.sentence-item');
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
            animateEffect(col, 'column');
            columnCompletedThisMove = true; // Set flag for this move
            let numLabel = completeSentenceCount;
            counters.forEach((item) => {
                item.classList.remove('active');
            });
            let thisCol = document.getElementById('sentence_' + completeSentenceCount);
            if (thisCol) {
                thisCol.innerHTML = sentenceCounter.replace(/[^\w\s]|_/g, '');
                // thisCol.classList.add('active');
                // let contentEl = thisCol.querySelector('.content');
                // if (contentEl) {
                    // contentEl.innerHTML = completeSentenceCount + '. ' + sentenceCounter;
                    //contentEl.innerHTML = completeSentenceCount + '. ' + sentenceCounter + '(column)';
                // }
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
    const counters = document.querySelectorAll('.sentence-item');
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
            animateEffect(index, 'sentence');
            sentenceCompletedThisMove = true; // Set flag for this move
            counters.forEach((item) => {
                item.classList.remove('active');
            });
            let thisRow = document.getElementById('sentence_' + completeSentenceCount);
            if (thisRow) {
                thisRow.innerHTML = sentenceCounter.replace(/[^\w\s]|_/g, '');//sentenceCounter;
                // thisRow.classList.add('active');
                // let contentEl = thisRow.querySelector('.content');
                // if (contentEl) {
                    // contentEl.innerHTML = completeSentenceCount + '. ' + sentenceCounter;
                // }
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
    
    lastPuzzleID = currentPuzzleID;
    const items = document.querySelectorAll('.grid-item');
    const isSolved = Array.from(items).every(
        (item, index) => item.textContent === originalPositions[index]
    );
    if (isSolved) {
        // animateEffect(0, 'puzzle');
        showFinalScoreScreen();
        if(!ispuzzleAcadamy){
            startConfetti();
            document.getElementById('confetti-canvas').style.opacity = '1';
        
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
    }
}

function checkPuzzleHelth(originalPositions) {
    if (incorrectCounterValue >= health) {
        isFailed = 1;
    }
    if (isFailed) {
        showFinalScoreScreen();
    }
}

// **Animate the Effect for Completed Sections**
function animateEffect(index, type) {
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
    
    
    // Apply the animation with staggered timing
    elements.forEach((element, i) => {
        setTimeout(() => {
            // element.classList.add('wave-bounce');
            setTimeout(() => {
                // element.classList.remove('wave-bounce');
                element.classList.add('completed');
            }, 600);
        }, i * 100);
    });
    setTimeout(() => {
        // Wait for animationQueued to complete before continuing
        animationQueued(elements);       
    }, 1000); 
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
        // finalPoemContent.style.display = 'block';
        reviewHeight = reviewHeight + parseInt(addHeight);
        if (mainHeight <= reviewHeight) {
            mainHeight = reviewHeight;
        }
        console.log('game height ' + mainHeight);
    } else {
        if (mainHeight <= 740) {
            mainHeight = 740;
        }
        // finalPoemContent.style.display = 'none';
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

function showFinalScoreScreen() {
    const isAcademy = ispuzzleAcadamy;
    const delay = isFailed ? 1000 : 2000;

    setTimeout(() => {
        if (isFailed) {
            handleFailure(isAcademy);
        } else {
            handleSuccess(isAcademy);
        }
    }, delay);
}

/* -----------------------------
   HANDLERS
----------------------------- */

function handleFailure(isAcademy) {
    gameRow.style.display = 'none';
    finalScoreScreen.style.display = 'flex';
    document.body.classList.add('final-result-faild');
    finalScoreScreen.classList.add('final-result-faild');
    document.getElementById("share-button")?.style.setProperty('display', 'none');
    finalScoreSentence.querySelectorAll("li:empty").forEach(li => li.remove());

    if (isAcademy) {
        showAcademyFailure();
    } else {
        showRegularFailure();
    }
}

function handleSuccess(isAcademy) {
    if (!isAcademy) {
        showRegularSuccess();
    } else {
        showAcademySuccess();
    }
}

/* -----------------------------
   FAILURE SCREENS
----------------------------- */

function showAcademyFailure() {
    let title =  document.querySelector('#main-section').getAttribute('data-puzzle-title') || ''; 
    let fullPoemHTML = '';
    if(title != ''){
        fullPoemHTML = `<div class="overlay-current-poem"><span><strong>Correct Puzzle Sentence: </strong></span>${title}</div>`;
    }
    if(document.getElementById('resultFailed')){
        document.getElementById('resultFailed').remove();
    }
    const failedHTML = `
        <div id="resultFailed">
            <div class="overlay-title">Good News:<br>You Can Retry Academy Puzzles</div>
            <div class="overlay-subtitle">Give it another go!</div>
            <a id="replay-game" class="site-btn btn-replay">Replay</a>
        </div>`;
    finalScoreScreen.insertAdjacentHTML('beforeend', failedHTML);
    backToPuzzle.style.display = 'none';
    finalScoreSentence.innerHTML = '';
    finalScoreSentence.style.display = 'none';
    const replayGame = document.getElementById('replay-game');
    replayGame?.addEventListener('click', () => {
        const level = GameStorageService.getItem('puzzle-acadamy-level');
        startGameAgain(level, true);
    });
}

function showRegularFailure() {
    //TODO
    puzzleId = document.querySelector('#main-section').getAttribute('data-puzzle-id');
   
    restartGame(puzzleId , 0, false);
    console.log('showRegularFailure');
    let title =  document.querySelector('#main-section').getAttribute('data-puzzle-title') || ''; 
    let fullPoemHTML = '';
    if(title != ''){
        fullPoemHTML = `<div class="overlay-current-poem"><span><strong>Correct Puzzle Sentence: </strong></span>${title}</div>`;
    }
    if(document.getElementById('resultFailed')){
        document.getElementById('resultFailed').remove();
    }
        const failedHTML = `
        <div id="resultFailed">
            <div class="overlay-title">The Puzzle Wins This Round</div>
            <div class="overlay-subtitle">Try a new Walla tomorrow!</div>
        </div>`;
    finalScoreScreen.insertAdjacentHTML('afterbegin', failedHTML);

    resetUIForFinalScreen();
    document.body.classList.add('final-result');
    document.body.classList.remove('final-result-faild');
    backToPuzzle.removeAttribute('style');
    document.getElementById("share-button").style.display = "none";
    saveOrLoadPuzzleStatus(false);
    highlightScoreRow();
    initFinalScreenToggles();
}

function restartGame(puzzleId =0 , puzzleCounterValue, isAcadamy = false) {
    console.log('restartGame called');
    document.querySelector('#final-score-screen .finish-buttons').style.display = 'none';
    document.querySelector('#final-score-screen .finish-buttons #share-button').style.display = 'none';
    
    // Header icon hide
    document.querySelector('#difficulty-popup').classList.remove('active-difficulty-level-game');
    
    gameLevelIcon.style.display= 'none';


    document.querySelector('#game-prompt').style.display = 'none';
    pageHeader.classList.add('playing');
    moveCounterDisplay.classList.remove('over');
    finalScoreScreen.style.display = 'none';
    moveCounterDisplay.style.display = "block"; 
    sentenceCounterDisplay.style.display = "block";
    backToResult.style.display = 'none';
    // errorCounterDisplay.style.display = "block";
    // errorCounterDisplay.innerHTML = '';
    ispuzzleAcadamy = isAcadamy;
    if(isAcadamy){
        url = localVars.ajax_url + '?action=wallawords_get_puzzle_data&is_acadamy=' + isAcadamy + '&nonce=' + localVars.nonce;
        puzzleAcadamyLevel = GameStorageService.getItem('puzzle-acadamy-level');
        if(puzzleAcadamyLevel){
            url += '&level=' + puzzleAcadamyLevel;
        }
    }else{
        if (puzzleId !== 0 ) {
            hasID = puzzleId;
            url = localVars.ajax_url + '?action=wallawords_get_puzzle_data&gameID=' + hasID + '&nonce=' + localVars.nonce;
            currentPuzzleID = hasID;
        } else {
            url = localVars.ajax_url + '?action=wallawords_get_puzzle_data&nonce=' + localVars.nonce;
        }
        if (completedSessionPuzzles) {
            url += '&completed=' + completedSessionPuzzles;
        }
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('preview') === 'true') {
            url += '&preview=true';
        }
    }
    if (lastPuzzleID !== 0) {
        url += '&lastPuzzleID=' + lastPuzzleID;
    }
    gameScreen.style.display = 'flex';
    const floatingPieces = document.querySelectorAll('.piece');
    floatingPieces.forEach((piece) => {
        piece.classList.add('blur'); //change floating elements
    });
    //reset values on start
    moveCounterValue = 0;
    completeSentenceCount = 0;
    incorrectCounterValue = 0;
    isFailed = 0;
    completedSentences = [];
    completedColumns = [];
    // if (gameGrid) {
    //     gameGrid.destroy();
    //     const resetSentenceCounter = document.querySelectorAll('.sentence-item');
    //     resetSentenceCounter.forEach((row) => {
    //         row.remove();
    //     });
    // }
    jQuery.ajax({
        url: url,
        type: 'GET',
        success: async function (response) {
           
            let data = response.data;
            if (response.success === 'true') {
                
                try {
                    data = JSON.parse(response.data);
                } catch (err) {
                    console.error("JSON.parse failed:", err, response.data);
                    return;
                }
            }
            if (data) {
                
                try {
                    await dpData(data, localVars.nonce);
                } catch (err) {
                    console.error("Puzzle failed:", err);
                }
                const puzzles = await dpData(data, localVars.nonce);
                sentenceToggle.forEach(item => {
                    item.style.display = "block";
                });
                document.body.classList.add('steps-page');
                const originalDropPlacement = new Map();
                puzzleCounterValue = puzzles.length > puzzleCounterValue ? puzzleCounterValue : puzzles.length - 1;
                var selectedPoem = puzzles[puzzleCounterValue];
                activePoem = selectedPoem;
                currentPuzzleID = selectedPoem.id; //poem ID 
                var playStatus = GameStorageService.getItem(`puzzleStatus${currentPuzzleID}`);
                if(playStatus != null && !ispuzzleAcadamy){
                    gameGridElement.innerHTML = `<span></span><img src="${localVars.site_url}/wp-content/themes/wallawords/assets/src/images/spinner.svg" class="loading"><span></span>`;
                    gameRow.style.display = 'none';
                }
                // if(playStatus !== 'null' || playStatus !== 'undefine')
                title = selectedPoem.title; // Store the puzzle title

                originalPositions = selectedPoem.correctWords.slice(); // Store the original positions
                // console.log(selectedPoem.correctWords);
                lockedWords = selectedPoem.lockedWords.slice(); // Store the locked positions
                sentences = selectedPoem.sentences; // Store sentences from JSON
                columns = selectedPoem.columns; //store column rows
                totalSentenceCount = selectedPoem.sentences.length + 3; //add 3 because we always have 3 vertical sentences in a puzzle
                
                
                titleDisplay.textContent = selectedPoem.title;
                gamePrompt.innerHTML = selectedPoem.prompt;
                gameGridElement.innerHTML = ''; // Clear previous words
                health = selectedPoem.health;
                originalPositions.forEach((word, index) => {
                    const div = document.createElement('div');
                    let clean = word.replace(/\s+/g, '');
                    if(clean.length > 8){
                        div.classList.add('min-nine-characters');
                    }
                    
                    div.classList.add('grid-item');
                   
                        div.textContent = word;
                        div.classList.add('correct-position'); // Mark it as correctly placed
                        div.classList.add('locked-position'); // Mark it as locked
                        div.classList.add('intro1');
                        div.style.animationDelay = `${parseInt(index) * .01}s`; //add delay to float in effect to stagger tiles  
                     
                    gameGridElement.appendChild(div);
                });
                
                document.querySelector('#final-score-screen .finish-buttons').style.display = 'block';
                const shareResultBtn = document.querySelector('#final-score-screen .finish-buttons #share-button');

                if (getComputedStyle(shareResultBtn).display === 'none') {
                    shareResultBtn.style.display = 'block';
                }
                
                
            }
        }
    });

}

/* -----------------------------
   SUCCESS SCREENS
----------------------------- */

function showRegularSuccess() {
    puzzleCounter++;
    resetUIForFinalScreen();
    document.body.classList.add('final-result');
    document.getElementById('resultFailed')?.remove();
    document.body.classList.remove('final-result-faild');
    finalScoreScreen.classList.remove('final-result-faild');
    document.getElementById("share-button").removeAttribute('style');
    backToPuzzle.removeAttribute('style');
    
    saveOrLoadPuzzleStatus(true);
    highlightScoreRow();
    initFinalScreenToggles();
}

function showAcademySuccess() {
    document.body.classList.add('final-acadamy-result');
    if (!finalAcadamyPuzzlePopup.classList.contains('active')) {
        finalAcadamyPuzzlePopup.classList.add('active');
        finalAcadamyPuzzlePopup.removeAttribute('style');

        let level = GameStorageService.getItem('puzzle-acadamy-level');
        const nextLevelNum = Math.min(level + 1, 4);
        const popupData = getAcademyPopupData(level);
        finalAcadamyPuzzlePopup.querySelector('.popuptitle').innerHTML = popupData.title;
        finalAcadamyPuzzlePopup.querySelector('.popupcontent').innerHTML = popupData.content;
        finalAcadamyPuzzlePopup.querySelector('.note').innerHTML = popupData.note;
        nextLevel.innerText = popupData.button;

        levelDone.innerText = `${level}/3`;
        updateLevelBadge(level);
        GameStorageService.setItem('puzzle-acadamy-level', nextLevelNum > 3 ? 'done' : nextLevelNum, 1);
    }
}

/* -----------------------------
   HELPERS
----------------------------- */

function resetUIForFinalScreen() {
    setTimeout(() => {
        titleDisplay.removeAttribute('style');
        kicker.removeAttribute('style');
        gameRow.style.display = 'none';
        moveCounterDisplay.innerHTML = `Health:`;
        finalScoreScreen.style.display = 'flex';
        sentenceCounterDisplay.style.display = "none";
        resultSentenceCounterDisplay.removeAttribute('style');
    }, 1000);
}

function saveOrLoadPuzzleStatus(status) {
    const key = `puzzleStatus${currentPuzzleID}`;
    const existingStatus = GameStorageService.getItem(key);
    
    if (existingStatus != null && !ispuzzleAcadamy) {
        const savedHTML = GameStorageService.getItem(`finalScoreSentence${currentPuzzleID}`);
        finalScoreSentence.style.display = savedHTML ? "" : "none";
        if (savedHTML) finalScoreSentence.innerHTML = savedHTML;
        const gSavedHTML = GameStorageService.getItem(`gameGrid${currentPuzzleID}`);
        if (gSavedHTML) gameGridElement.innerHTML = gSavedHTML;
    } else {
        const sHTML = finalScoreSentence.innerHTML;
        finalScoreSentence.style.display = sHTML ? "" : "none";
        GameStorageService.setItem(key, status, 1);
        // TODO

        const difficulty = difficultyPopup.querySelector('.level-tabs .active').getAttribute('data-level');
        if (difficultySessionPuzzles.includes(difficulty) === false) {
            difficultySessionPuzzles.push(difficulty);
        }
        if (GameStorageService.getItem('difficultySessionPuzzles'+`${currentPuzzleID}`)) {
            if (GameStorageService.getItem('difficultySessionPuzzles'+`${currentPuzzleID}`).indexOf(difficultySessionPuzzles) == -1) { 
                GameStorageService.setItem('difficultySessionPuzzles'+`${currentPuzzleID}`, difficultySessionPuzzles, 365);
            }
        } else {
            GameStorageService.setItem('difficultySessionPuzzles'+`${currentPuzzleID}`, difficultySessionPuzzles, 365);
        }
        
        GameStorageService.setItem(`finalScoreSentence${currentPuzzleID}`, sHTML, 1);
        GameStorageService.setItem(`helth${currentPuzzleID}`, incorrectCounterValue, 1);
        
        const classesToRemove = ['zingle', 'warning', 'bounce'];
        gameGridElement.querySelectorAll('*').forEach(el => {
            el.classList.remove(...classesToRemove);
        }); 
        const gmHTML = gameGridElement.innerHTML;
        GameStorageService.setItem(`gameGrid${currentPuzzleID}`, gmHTML, 1);        
    }
}

function highlightScoreRow() {
    const scoreTableElements = document.querySelectorAll('.score-row');
    scoreTableElements.forEach(row => {
        const from = parseInt(row.dataset.from);
        const to = parseInt(row.dataset.to);
        const moves = parseInt(moveCounterValue);
        if (moves >= from && moves <= to) {
            row.classList.add('highlighted', row.id);
            finalMoveCount.classList.add(row.id);

            const icon = row.querySelector(".score-icon")?.innerHTML.trim() || "";
            const rank = row.querySelector(".score-title")?.innerHTML.trim() || "";
            completedPuzzleIcon = icon;
            completedPuzzleRank = rank;
        }
    });
}

function initFinalScreenToggles() {
    const toggles = document.querySelectorAll('.final-page-toggle .toggle');
    const scoreTableElement = document.querySelector('.score-table');

    toggles.forEach(toggle => {
        toggle.addEventListener('click', () => {
            toggles.forEach(t => t.classList.remove('active'));
            if (toggled === 'results') {
                scoreTableElement.style.display = 'flex';
                toggled = 'review';
                toggles[0].classList.add('active');
                gameGridElement.classList.remove('active');
            } else {
                scoreTableElement.style.display = 'none';
                toggled = 'results';
                toggles[1].classList.add('active');
                gameGridElement.classList.add('active');
            }
        });
    });
}

function updateLevelBadge(level) {
    const prev = `level-${level - 1}`;
    if (levelBadge.classList.contains(prev)) {
        levelBadge.classList.remove(prev);
        levelBadge.classList.add(`level-${level}`);
    }
}

function getAcademyPopupData(level) {
    if (level === 3) {
        return {
            title: 'You <span>did it!</span>',
            content: `You’ve mastered all 3 levels! <br> Great job on completing the challenge.`,
            button: `Play Today's Puzzle`,
            note: `Can you beat your best score? Limit your moves and aim for perfection!`
        };
    }
    if (level === 1) {
        return {
            title: 'Well <span>done</span>',
            content: `You’ve completed Level ${level}! <br>Get ready for Level ${level + 1} and challenge yourself even more.`,
            button: 'Next Level',
            note: `Take your time. Walla is about strategy, not quickness!`
        };
    }
    return {
        title: 'Well <span>done</span>',
        content: `You’ve completed Level ${level}! <br>Get ready for Level ${level + 1} and challenge yourself even more.`,
        button: 'Next Level',
        note: `Remember to limit moves and avoid mistakes to get the highest score!`
    };
}


function getTileBounds(tile, padding = 15, Offset = 0) {
    const rect = tile.getBoundingClientRect();
    return {
        left: (rect.left + window.scrollX - padding) * 100,
        top: (rect.top + window.scrollY - padding + Offset) * 100,
        right: (rect.right + window.scrollX + padding) * 100,
        bottom: (rect.bottom + window.scrollY + padding + 4 + Offset) * 100 // Add 4px fudge factor to bottom
    };
}

// Animation

let isRunning = false;
let queue = [];

async function animationQueued(elements) {
  // Push this animation request into the queue
  queue.push(elements);

  // If an animation is already in progress, exit (it will handle the queue)
  if (isRunning) return;

  isRunning = true;

  while (queue.length > 0) {
    const current = queue.shift(); // take first item in queue

    try {
    //   console.log("Running animation for:", current);
      // If outlineSegment is async → await it
      await outlineSegment(current);
    //   await new Promise(resolve => setTimeout(resolve, 2500));
    } catch (error) {
      console.error("Error in animation:", error);
    }
  }

  isRunning = false;

  checkPuzzleCompletion(originalPositions);
  
}

function outlineSegment(items) {
    return new Promise(resolve => {
        setTimeout(() => {
            const grid = items[0]?.parentElement || document.getElementById("sortable-grid");
            const allItems = Array.from(grid.children);
            const hasAdminBar = document.body.classList.contains('admin-bar');
            const adminBarOffset = hasAdminBar ? -32 : 0;
            const padding = 15;
            allItems.forEach(tile => { if (!items.includes(tile)) tile.classList.add("dimmed"); });
            let paths = items.map(tile => {
                const b = getTileBounds(tile, padding, adminBarOffset);
                return [
                    { X: b.left, Y: b.top },
                    { X: b.right, Y: b.top },
                    { X: b.right, Y: b.bottom },
                    { X: b.left, Y: b.bottom }
                ];
            });
            const clipper = new ClipperLib.Clipper();
            clipper.AddPaths(paths, ClipperLib.PolyType.ptSubject, true);
            let solution = new ClipperLib.Paths();
            clipper.Execute(ClipperLib.ClipType.ctUnion, solution, ClipperLib.PolyFillType.pftNonZero, ClipperLib.PolyFillType.pftNonZero);
            let unionPoly;
            if (solution.length === 0) {
                let minL = Infinity, minT = Infinity, maxR = -Infinity, maxB = -Infinity;
                items.forEach(tile => {
                    const b = getTileBounds(tile, padding, adminBarOffset);
                    minL = Math.min(minL, b.left);
                    minT = Math.min(minT, b.top);
                    maxR = Math.max(maxR, b.right);
                    maxB = Math.max(maxB, b.bottom);
                });
                unionPoly = [
                    { X: minL, Y: minT },
                    { X: maxR, Y: minT },
                    { X: maxR, Y: maxB },
                    { X: minL, Y: maxB }
                ];
            } else {
                unionPoly = getLargestPath(solution);
            }
            unionPoly = rotatePathToTopLeft(unionPoly);
            // Offset the shape inward by a specified number of pixels (scaled to ClipperLib units)
            var offsetValue = 9 * 100; // 9px, scaled
            var co = new ClipperLib.ClipperOffset();
            co.AddPath(unionPoly, ClipperLib.JoinType.jtRound, ClipperLib.EndType.etClosedPolygon);
            var offsetPoly = new ClipperLib.Paths();
            co.Execute(offsetPoly, -offsetValue);
            if (offsetPoly.length > 0) {
                unionPoly = offsetPoly[0];
            }
            // Convert back to pixel units for SVG
            unionPoly = unionPoly.map(pt => ({ X: pt.X / 100, Y: pt.Y / 100 }));
            const pathString = toSvgPath(unionPoly);
            let roundedPath;
            try {
                // console.log('Using custom roundPathCorners utility');
                roundedPath = roundPathCorners(unionPoly, 15); // 10px radius
                if (!roundedPath) {
                    console.warn('Rounded path is empty!');
                }
            } catch (e) {
                console.error('Error during roundPathCorners:', e);
                roundedPath = toSvgPath(unionPoly);
            }
            const svgOverlay = document.getElementById("svgOverlay");
            if (!svgOverlay) {
                console.error('svgOverlay not found!');
            } else {
                // console.log('svgOverlay found, appending path');
            }
            const svgNS = "http://www.w3.org/2000/svg";
            let pathEl = document.createElementNS(svgNS, "path");
            pathEl.setAttribute("d", roundedPath);
            pathEl.setAttribute("fill", "none");
            pathEl.setAttribute("stroke", "#4CAF50");
            pathEl.setAttribute("stroke-width", "5");
            pathEl.setAttribute("stroke-linejoin", "round");
            svgOverlay.appendChild(pathEl);
            let segDuration = items.length * 0.20;
            let extraPause = items.length * 0.20;
            pathEl.style.animationDuration = segDuration + "s";
            const length = pathEl.getTotalLength();
            pathEl.style.strokeDasharray = length;
            pathEl.style.strokeDashoffset = length;
            pathEl.style.setProperty('--dash-length', length);
            pathEl.classList.add("snake-path");
            setTimeout(() => {
                svgOverlay.removeChild(pathEl);
                allItems.forEach(tile => tile.classList.remove("dimmed"));
                resolve(segDuration + extraPause);
            }, (segDuration + extraPause) * 1000);
        }, 200);
    });
}

function toSvgPath(points) {
    return points.map((pt, i) =>
        (i === 0 ? "M" : "L") + pt.X + " " + pt.Y
    )
        .join(" ") + " Z";
}

function getLargestPath(paths) {
    return paths.reduce((largest, current) =>
        Math.abs(ClipperLib.Clipper.Area(current)) > Math.abs(ClipperLib.Clipper.Area(largest)) ?
            current : largest, paths[0]);
}

function rotatePathToTopLeft(points) {
    let minY = Infinity
        , minX = Infinity
        , idx = 0;
    points.forEach((pt, i) => {
        if (pt.Y < minY || (pt.Y === minY && pt.X < minX)) {
            minY = pt.Y;
            minX = pt.X;
            idx = i;
        }
    });
    return idx > 0 ? points.slice(idx)
        .concat(points.slice(0, idx)) : points;
}

// Utility to round corners of a polygon path
function roundPathCorners(points, radius) {
    if (points.length < 2) return '';
    // Find the index of the point closest to top-left (smallest Y, then X)
    let minIdx = 0;
    for (let i = 1; i < points.length; i++) {
        if (
            points[i].Y < points[minIdx].Y ||
            (points[i].Y === points[minIdx].Y && points[i].X < points[minIdx].X)
        ) {
            minIdx = i;
        }
    }
    // Rotate points so the path starts from the top-left
    const ordered = points.slice(minIdx).concat(points.slice(0, minIdx));
    let d = '';
    const len = ordered.length;
    for (let i = 0; i < len; i++) {
        const p0 = ordered[(i - 1 + len) % len];
        const p1 = ordered[i];
        const p2 = ordered[(i + 1) % len];
        // Vectors
        const v1 = { x: p1.X - p0.X, y: p1.Y - p0.Y };
        const v2 = { x: p2.X - p1.X, y: p2.Y - p1.Y };
        // Normalize
        const len1 = Math.hypot(v1.x, v1.y);
        const len2 = Math.hypot(v2.x, v2.y);
        const v1n = { x: v1.x / len1, y: v1.y / len1 };
        const v2n = { x: v2.x / len2, y: v2.y / len2 };
        // Start and end of the corner arc
        const start = {
            x: p1.X - v1n.x * Math.min(radius, len1 / 2),
            y: p1.Y - v1n.y * Math.min(radius, len1 / 2)
        };
        const end = {
            x: p1.X + v2n.x * Math.min(radius, len2 / 2),
            y: p1.Y + v2n.y * Math.min(radius, len2 / 2)
        };
        if (i === 0) {
            d += `M${start.x},${start.y}`;
        } else {
            d += `L${start.x},${start.y}`;
        }
        d += `Q${p1.X},${p1.Y},${end.x},${end.y}`;
    }
    d += 'Z';
    return d;
}