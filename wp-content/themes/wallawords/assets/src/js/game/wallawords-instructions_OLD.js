// **Tutorial Instructions and Images**
const instructions = [
    'Welcome to the chaos of a scrambled WallaWord puzzle!',
    "<span style='color:lightgreen'>Green words?</span> Lucky you—they're already in place.",
    "Drag the misplaced words to form sentences <span style='color:orangered'>left to right.</span>",
    "Sentences are also hiding <span style='color:deeppink'>top to bottom.</span> Ignore punctuation when reading vertically",
    "<h2>Now you try!</h2>Swap words to finish the incomplete <span style='color:orangered'>horizontal</span> sentence.",
    "Good! Now finish the incomplete <span style='color:deeppink'>vertical</span> sentence. Ignore punctuation"
];

const instructionSteps = document.getElementById('instruction-steps');

function startInstructions(slide) {

    let totalSlides = instructions.length;

    const instructionTabs = document.querySelectorAll('.instruction-steps .step');

    if(instructionTabs.length == 0) {
        for(s=0;s<totalSlides;s++){
            const div = document.createElement('div');
            div.id = s;
            div.classList.add('step');
            if(s == 0) {
                div.classList.add('active'); 
            }

            div.addEventListener('click', (e) => {
                startInstructions(e.target.id);
                console.log('change to slide: '+e.target.id);
            });

            instructionSteps.appendChild(div);
        }
    }

    jQuery.ajax({
        url: '/wp-admin/admin-ajax.php?action=wallawords_get_instruction_data',
        type: 'GET',
        success: function (response) {

            if(response){

                console.log(JSON.parse(response));

                const introPuzzle = JSON.parse(response);

                console.log('slide #'+slide);

                let itemsCt = 0;

                instructionTabs.forEach(item => {
                    if(itemsCt == slide) {
                        item.classList.add('active');
                    } else {
                        item.classList.remove('active');
                    }
                    itemsCt++;
                });

                if (slide!==0){
                    backButton.style.display = 'block';
                } else {
                    backButton.style.display = 'none';
                }
                if (slide===5){
                    playButton.style.display = 'block';
                    playButton.disabled = true;
                    nextButton.style.display = 'none';
                    skipButton.style.display = 'none';
                } else {
                    playButton.style.display = 'none';
                    nextButton.style.display = 'block';
                    skipButton.style.display = 'block';
                }

                const selectedPoem = introPuzzle[slide];
                instructionText.innerHTML = `${instructions[slide]}`;

                instructionPositions = selectedPoem.correctWords.slice(); // Store the original positions
                lockedPuzzleWords = selectedPoem.lockedWords.slice(); // Store the original positions

                const lockedIndexes = selectedPoem.lockedWords;
                const shuffledWords = selectedPoem.incorrectWords;

                // Populate the grid with words
                const grid = document.getElementById('instructions-sortable-grid');
                grid.innerHTML = ''; // Clear previous words

                instructionPositions.forEach((word, index) => {
                    const div = document.createElement('div');
                    div.classList.add('grid-item');
                    div.classList.add('disabled');
                    if (lockedIndexes.includes(index)) {
                        if (slide===4 && index > 3) {
                            div.innerHTML = `<span class="blurred">${word}</span>`;
                        } else if (slide===5 && ![0,3,6,9,12].includes(index)) {
                            div.innerHTML = `<span class="blurred">${word}</span>`;
                        } else {
                            div.innerHTML = `${word}`;
                        }
                        div.classList.add('correct-position'); // Mark it as correctly placed
                        if (slide===2 || slide===4) {
                            div.classList.add('orange'); // Mark it as correctly placed
                        }
                        if (slide===3 || slide===5) {
                            div.classList.add('pink'); // Mark it as correctly placed
                        }
                    } else {
                        div.textContent = shuffledWords.shift(); // Populate with shuffled words
                    }

                    if(slide > 3){
                        div.classList.remove('disabled');
                    }

                    grid.appendChild(div);
                });

                if (slide===4 || slide===5) {
                    nextButton.disabled = true;
                    if (instructionGrid) {
                        instructionGrid.destroy();
                        instructionGrid = null
                    }
                    // Initialize SortableJS on the grid container with Swap plugin
                    instructionGrid = new Sortable(grid, {
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
                            updateInstructionsPuzzle(evt, instructionPositions);
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
                        }
                    });
                } else {
                    nextButton.disabled = false;
                }
                instructionSlide++;
            }
        }
        
    });
        
}

function updateInstructionsPuzzle(evt, originalPositions) {
    // Reset flags for this move
    columnCompletedThisMove = false;
    sentenceCompletedThisMove = false;

    // Get the indices of moved items
    const fromIndex = evt.oldIndex;
    const toIndex = evt.newIndex;

    // Update positions and visual feedback
    if (instructionSlide===5) {
        if (toIndex===2) checkCorrectPositionAtIndex(toIndex, originalPositions);
        if (fromIndex===2) checkCorrectPositionAtIndex(fromIndex, originalPositions);
        checkSentenceCompletionInstructions(originalPositions);
    }

    if (instructionSlide===6) {
        if (toIndex===9) checkCorrectPositionAtIndex(toIndex, originalPositions);
        if (fromIndex===9) checkCorrectPositionAtIndex(fromIndex, originalPositions);
        checkColumnCompletionInstructions(originalPositions);
    }
}

function checkColumnCompletionInstructions(originalPositions) {
    const items = document.querySelectorAll('.grid-item');
    const columns = 3; // Assuming a 3-column grid, adjust as necessary
    const rows = 15 / columns;

    // Check each column to see if it's fully completed
    for (let col = 0; col < columns; col++) {
        // if (completedColumns.includes(col)) continue;
        let isColumnCorrect = true;

        for (let row = 0; row < rows; row++) {
            const index = row * columns + col;
            if (items[index].textContent !== originalPositions[index]) {
                isColumnCorrect = false;
                // break;
            }
        }
        if (isColumnCorrect && col===0) {
            playButton.disabled = false;
            animateWaveEffectInstructions(col, 'column');
        }
    }
}

function checkSentenceCompletionInstructions(originalPositions) {
    const items = document.querySelectorAll('.grid-item');

    // Check each sentence to see if it's fully completed
    for (let index = 0; index < 3; index++) {
        let isSentenceCorrect = true;
        if (completedSentences.includes(index)) continue;

        for (let i = 0; i <= 4; i++) {
            if (items[i].textContent !== originalPositions[i]) {
                isSentenceCorrect = false;
                // break;
            }
        }

        if (isSentenceCorrect && index===0) {
            // completedSentences.push(index);
            // completeSentenceCount ++;
            animateWaveEffectInstructions(index, 'sentence');
            sentenceCompletedThisMove = true; // Set flag for this move
            // break;
            nextButton.disabled = false;
        }
    }
}

function animateWaveEffectInstructions(index, type) {
    const items = document.querySelectorAll('.grid-item');
    let elements = [];

    // Determine elements based on type
    if (type === 'column') {
        for (let row = 0; row < items.length / 3; row++) {
            elements.push(items[row * 3 + index]);
        }
    } else if (type === 'sentence') {
        // const [start, end] = sentences[index];
        for (let i = 0; i <= 3; i++) {
            elements.push(items[i]);
        }
    } else if (type === 'puzzle') {
        elements = Array.from(items);
    }

    // Apply the wave-bounce animation with staggered timing
    elements.forEach((element, i) => {
        if (instructionSlide===5) element.classList.add('orange')
        if (instructionSlide===6) element.classList.add('pink')
        setTimeout(() => {
            element.classList.add('wave-bounce');
            setTimeout(() => {
                element.classList.remove('wave-bounce');
            }, 600);
        }, i * 100);
    });
}