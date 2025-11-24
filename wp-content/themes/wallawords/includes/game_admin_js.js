//constant vars
let lockedWords = 0;
let wordCountTtl = 0;
let maxLocked = 13;
let isComplete = true;

//count words in the textbox
function countWords() {
    const textArea = jQuery('#full-poem');
    const wordCountDisplay = jQuery('#word-count');
    
    // Get the value of the textarea and trim whitespace
    const text = textArea.val().trim();
    
    // Count words by splitting the text on spaces and filtering out empty elements
    const wordCount = text.length > 0 ? text.split(/\s+/).length : 0;

    if(wordCount > 0 && wordCount < 15 ){
        wordCountDisplay.addClass('red');
        //
        jQuery('#publish').attr("disabled",'disabled');
        jQuery('#publish').addClass('readonly');
    }

    if(wordCount == 15) {
        wordCountDisplay.addClass('green');
        wordCountDisplay.removeClass('red');
        //enable buttons if valid
        jQuery('#publish').attr("disabled",null);
        jQuery('#publish').removeClass('readonly');
    }

    if(wordCount > 15 ){
        wordCountDisplay.addClass('red');
        wordCountDisplay.removeClass('green');
        //
        jQuery('#publish').attr("disabled",'disabled');
        jQuery('#publish').addClass('readonly');
    }
    
    // Update the word count display
    wordCountDisplay.html(`Word Count: ${wordCount}`);
    wordCountDisplay.attr('data-count',wordCount); 
    wordCountTtl = wordCount;

    console.log(wordCountTtl);
}

function updateBlockIdsAndNames($container) {
    $container.find('.block').each(function (index) {
        const $block = jQuery(this);
        const $inputTxt = $block.find('input[type="text"]');
        const $inputChk = $block.find('input[type="checkbox"]');
        const $label = $block.find('label');

        if($label.length){
            const newLabel = `locked_${index}`;
            $label.attr('for',newLabel);
            $label.on('click', function (e) {
                const $checkbox = jQuery(this).parent().find('input[type="checkbox"]');
                if ($checkbox.is(':checked')) {
                    if(lockedWords < maxLocked){
                        $checkbox.closest('.block').addClass('locked');
                    }else{
                        $checkbox.prop('checked', false);
                    }
                } else {
                    if(lockedWords > 0){
                        $checkbox.closest('.block').removeClass('locked');
                    }
                }

                if(lockedWords == maxLocked) {
                    e.preventDefault();
                }
                
                    
            });
        }

        if ($inputChk.length) {
            const newId = `locked_${index}`;
            const newName = `locked_${index}`;
            $inputChk.attr('id', newId);
            $inputChk.attr('name', newName);
            $inputChk.val(index);
        }

        if ($inputTxt.length) {
            const newId = `incorrect_${index}`;
            const newName = `incorrect_${index}`;
            $inputTxt.attr('id', newId);
            $inputTxt.attr('name', newName);
        }

        $block.attr('data-index', index);

    });
}

//does a deeper shuffle to ensure the orignial values change as much as possible
function randomizeBlocks() {
    const $container = jQuery('.game-grid');
    const $blocks = $container.find('.block:not(.locked)');
    const originalValues = [];
    const randomizedValues = [];

    //original values
    $blocks.find('input[type="text"]').each(function(item) {
        originalValues.push(jQuery(this).val());
    });        

    //shuffled array of originals w/ array map to match against values
    const shuffledIndices = originalValues.map((_, index) => index);
    for (let i = shuffledIndices.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [shuffledIndices[i], shuffledIndices[j]] = [shuffledIndices[j], shuffledIndices[i]];
    }

    // Ensure no value is in its original position
    for (let i = 0; i < shuffledIndices.length; i++) {
        if (shuffledIndices[i] === i) {
            // Swap with the next element or the first element if it's the last
            const swapIndex = i === shuffledIndices.length - 1 ? 0 : i + 1;
            [shuffledIndices[i], shuffledIndices[swapIndex]] = [shuffledIndices[swapIndex], shuffledIndices[i]];
        }
    }

    $blocks.each(function (index) {
        const newValue = originalValues[shuffledIndices[index]];
        randomizedValues.push(newValue);
        jQuery(this).find('input[type=text]').val(newValue);
    });

    updateBlockIdsAndNames($container);
}

function checkBlockPlacementStates() {
    const $container = jQuery('.game-grid');
    const $blocks = $container.find('.block:not(.locked)');
    const originalValues = [];
    const randomizedValues = [];
    const matchedValues = [];
  
    $container.find('.correct').each(function(item) {
        originalValues.push(jQuery(this).val());
    });        

    //create a new array the same length containing current non-locked tile values
    for (let i = 0; i < originalValues.length; i++) {
        if(jQuery('#incorrect_'+i).parent().parent().hasClass('locked')) {
            randomizedValues[i] = '';
        } else {
            randomizedValues[i] = jQuery('#incorrect_'+i).val();
        }
    }

    // Ensure no value is in its original position    
    for (let i = 0; i < originalValues.length; i++) {
        if (randomizedValues[i] === originalValues[i]) {
            matchedValues.push("<em>"+randomizedValues[i]+"</em> in position ["+(i+1)+"]");
        }
    }   

    //return matches if found or validate true
    if(matchedValues.length > 0) {
        return matchedValues;
    } else {
        return true;
    }  
    
}

function toggleLockedItem(item) {
    const $checkbox = item;
    
    if ($checkbox.is(':checked')) {
        
        if(lockedWords < maxLocked){
            $checkbox.closest('.block').addClass('locked');
            lockedWords++;
        }else{
            $checkbox.prop('checked', false);
        }
    } else {
        if(lockedWords > 0){
            $checkbox.closest('.block').removeClass('locked');
            lockedWords--;
        }
    }

    if(getBlockTilesBaseDifficulty() == 0){
         jQuery('#randomize').fadeIn(500);
    }else{
         if(lockedWords >= getBlockTilesBaseDifficulty()) {
            jQuery('#randomize').fadeIn(500);
        } else {
            jQuery('#randomize').fadeOut(500);
        }   
    }
   

    updateSortableState(sortable);

}

function confirmReset() {
    return confirm('Clear current grid and reset to original state - are you sure??');
}

function updateSortableState(sortableInstance) {
    if (lockedWords >= getBlockTilesBaseDifficulty()) {
        isComplete = true;
        sortableInstance.option("disabled", false); // Enable sorting
        jQuery('#publishing-action .publish-lock').fadeOut(250);
    } else {
        isComplete = false;
        sortableInstance.option("disabled", true); // Disable sorting
        jQuery('#publishing-action .publish-lock').fadeIn(250);
    }
}

function getBlockTilesBaseDifficulty() {

    difficulty = jQuery('.acf-field[data-name="wwp_difficulty_settings"] input:checked').val();
    blockCount = jQuery('#tab-2').data(difficulty+'_block_tiles');
    blockCount = parseInt(blockCount);
    return blockCount;
}

    //init function
    jQuery(document).ready(function(){
        jQuery(document).on('click', '.acf-field[data-name="wwp_difficulty_settings"] input', function () {
        console.log("Clicked:", this.value, getBlockTilesBaseDifficulty());

        if(this.value == 'genius' ){
            jQuery('#randomize').fadeIn(500);
            jQuery('#publishing-action .publish-lock').fadeOut(250);
            jQuery('.min_feel_tiles').fadeOut(250);
        }else{
            if(jQuery('.block.locked').length) {
            lockedWords = jQuery('.block.locked').length;
            }
            console.log("Locked Words:", lockedWords);
            if(lockedWords >= getBlockTilesBaseDifficulty()) {
                
                jQuery('#publishing-action .publish-lock').fadeOut(250);
                jQuery('#randomize').fadeIn(250);
            }else{
                
                jQuery('#randomize').fadeOut(250);
                jQuery('#publishing-action .publish-lock').fadeIn(250);
            }

            if(getBlockTilesBaseDifficulty() != 0){
                jQuery('.min_feel_tiles').fadeIn(250);
                jQuery('.min_feel_tiles').html('').html('Select at least <span>'+getBlockTilesBaseDifficulty()+'</span> tiles to lock');
            }else{
                jQuery('.min_feel_tiles').fadeOut(250);
            }
        }
    });

     jQuery(document).ready(function(){
    //add div layer to control publisher box visibles
    const publishLocked = document.createElement('div');
    publishLocked.classList.add('publish-lock');   
    jQuery('#publishing-action').append(publishLocked);

    countWords();

    if(jQuery('.block.locked').length) {
        lockedWords = jQuery('.block.locked').length;
    }

    jQuery('#full-poem').on('input change blur focus',function(){
        var cleaned = jQuery(this).val().replace(/[^a-zA-Z0-9\s!,.?]/g, '');
        if (jQuery(this).val() !== cleaned) {
            jQuery(this).val(cleaned);
        }
        countWords();
    })

    if (isComplete == false || lockedWords <= getBlockTilesBaseDifficulty()) {
       jQuery('.publish-lock').css('display','block');
    }

    //run a validation first before allowing to submit
    jQuery('#publish').on('click',function(e) {
         
         difficulty = jQuery('.acf-field[data-name="wwp_difficulty_settings"] input:checked').val();
         
        if (isComplete == false || (lockedWords < getBlockTilesBaseDifficulty() && getBlockTilesBaseDifficulty() != 0)) {
            jQuery('.publish-lock').css('display','block');
            console.log('true');
         } else {
            console.log('false');
        
            let validated = checkBlockPlacementStates();

            if(validated == true) {
                return true;
            } else {
                console.log(validated);

                let validationList = '';

                for(l=0;l<validated.length;l++){
                    validationList += validated[l]+'<br>';
                }

                var msg = '<div id="message" class="notice notice-error error is-dismissible"><p><b>You still have tiles in their original positions:</b><br> '+validationList+'<br><em>Lock more tiles, or change positions to maximize gameplay!</em></p></div>';
                if(!jQuery('#tab-2').find('#message').length){
                    jQuery('#tab-2').prepend(msg);
                    
                    setTimeout(() => {
                        jQuery('#tab-2 #message').fadeOut(500, function(){
                            jQuery(this).remove();
                        });
                    }, 15000);
                }

                
                return;
            }
        }
        
            
    });
    
    const $container = jQuery('.game-grid');

    if ($container.length) {
        // Initialize SortableJS
        sortable = Sortable.create($container[0], {
            //group: 'unlocked',
            swap: true, // Enable swap plugin
            handle: '.handle',
            filter: '.locked',
            disabled: true, // Initially disabled until 3 blocks are locked
            preventOnFilter: true,
            animation: 150,
            onEnd: function () {
                updateBlockIdsAndNames($container);
            },          
            onFilter: function (evt) {
                //add some logic here when an item is filtered
                //console.log('Filtering out element:', evt.item);
            },                      
            onMove:function (evt) {
                if (evt.related){
                    return evt.related.className.indexOf('locked') === -1;
                }
            }
            
        });

         // Initial state check
        updateSortableState(sortable);
        
    }   

    jQuery('#randomize').on('click', function () {
        randomizeBlocks();
    });

    jQuery('#generate-puzzle').on('click', function () {

        countWords();
        
        var fullPoem = jQuery('#full-poem').val();
        var postID = jQuery('#post_ID').val();
        var resetText = '&fullReset=0';

        if(fullPoem != "" && wordCountTtl == 15){

            if(jQuery('.game-grid').html()) {
                var confirm = confirmReset();

                if(confirm == true) {
                    jQuery('.game-grid').html('');
                    
                    lockedWords = 0;
                    jQuery('#generate-puzzle .dashicons').css('display','inline-block');
                    //needs to do a full reset
                    resetText = '&fullReset=1';
                    updateSortableState(sortable);

                } else {
                    jQuery('#generate-puzzle .dashicons').css('display','none');
                    return;
                }
            } else {
                jQuery('#generate-puzzle .dashicons').css('display','inline-block');
            }           
            
            console.log('full poem text:'+fullPoem);
            
            //do ajax
            jQuery.ajax({
                type: "GET",
                url: localVars.ajax_url,
                data:"action=output_game_grid_for_admin&isAjax=1&postID="+postID+"&fullPoem="+fullPoem+resetText,
                success: function(response) {
                    if(response){	
                        //console.log('grid response:');
                        //console.dir(response);
                        jQuery('#tab-2').fadeIn(500);
                        jQuery('.game-grid').html(response.replace(/\\/g,""));
                        jQuery('.game-grid input[type=checkbox]').on('change', function(e) {
                            e.preventDefault();
                            toggleLockedItem(jQuery(this));
                        })   
                        jQuery('#generate-puzzle .dashicons').css('display','none');
                        jQuery('#generate-puzzle').html('Reset Grid &raquo;');

                    }
                }
            });

        } else {
            var msg = '<div id="message" class="notice notice-error error is-dismissible"><p>Please fill out the full poem!</p></div>';
            if(!jQuery('.puzzle-tab').find('#message').length){
                jQuery('.puzzle-tab').prepend(msg);
                
                setTimeout(() => {
                    jQuery('.puzzle-tab #message').fadeOut(500, function(){
                        jQuery(this).remove();
                    });
                }, 1500);
            }
            return;
        }

        difficulty = jQuery('.acf-field[data-name="wwp_difficulty_settings"] input:checked').val();
        if(difficulty != 'genius'){
            jQuery('#randomize').fadeOut(300);
        }else{
            jQuery('#randomize').fadeIn(300);

        }

    });
    
    jQuery('.game-grid input[type=checkbox]').on('change', function () {
        toggleLockedItem(jQuery(this));
    })   

});
});