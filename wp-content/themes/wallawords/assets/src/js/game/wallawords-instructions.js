// **Tutorial Instructions and Images**
const instructionScreen = document.getElementById('instruction-screen-wrapper');
const instructionSteps = document.getElementById('instruction-steps');
const instructionText = document.getElementById('instruction-text');
const header = document.querySelector('.instructions-container .header');

const helpButton = document.getElementById('help-button');
const skipButton = document.getElementById('skip-button');
const nextButton = document.getElementById('next-button');
const backButton = document.getElementById('back-button');
const playButton = document.getElementById('play-button');

let totalSlides = 0;
let instructionSlide = 1;

/*
instructionText.addEventListener('scroll', () => {
    if (instructionText.scrollTop > 95) {
        header.classList.add('scrolled');
    } else {
        header.classList.remove('scrolled');
    }
});
*/

helpButton.addEventListener('click', () => {
    //instructionSlide = 1;
    //startInstructions(instructionSlide);

    const instructionTextWrapper = document.getElementById('instruction-text');
    if (!instructionTextWrapper) return;

	let viewportHeight = window.visualViewport ? window.visualViewport.height : window.innerHeight;
	
	let offsetVal = 104;

    if(window.innerWidth > 747) {
        offsetVal = 164; //add 20px for larger size screens    
    }

    let resetInstructionsHeight = viewportHeight - offsetVal; // Adjust height

    instructionTextWrapper.style.height = `${resetInstructionsHeight}px`;

    //console.log('instruction screen height: '+resetInstructionsHeight);

    document.body.style.overflow = 'hidden';

    var helpScreen = jQuery('#instruction-screen-wrapper');

    if (helpScreen.hasClass('active')) {
        //gameMenu.css('display','none');
        helpScreen.fadeOut(250);
        helpScreen.removeClass('active');
    } else {
        //gameMenu.css('display','block');
        helpScreen.fadeIn(500);
        helpScreen.addClass('active');
        jQuery('#instruction-screen-overlay').css('z-index','999');
    } 
});

skipButton.addEventListener('click', () => {
    document.body.style.overflow = 'auto';
    var helpScreen = jQuery('#instruction-screen-wrapper');
    helpScreen.fadeOut(250);
    helpScreen.removeClass('active');
    //titleScreen.style.display = 'none';
    instructionScreen.style.display = 'none';
    /*
    //instructionSlide = 1;
    //switchInstructionsSlide(instructionSlide);
    if(activePoem.length == 0){
        startGame(puzzleCounter);
    }
    */
});

playButton.addEventListener('click', () => {
    document.body.style.overflow = 'auto';
    titleScreen.style.display = 'none';
    instructionScreen.style.display = 'none';
    instructionSlide = 1;
    //switchInstructionsSlide(instructionSlide);
    startGame(puzzleCounter);
});

// * previous instruction slideshow */
/*
backButton.addEventListener('click', (e) => {
    switchInstructionsSlide(parseInt(instructionSlide)-1);
});

nextButton.addEventListener('click', (e) => {
    switchInstructionsSlide(parseInt(instructionSlide)+1);
});

function startInstructions(slide) {

    /*

    jQuery.ajax({
        url: '/wp-admin/admin-ajax.php?action=wallawords_get_instruction_data',
        type: 'GET',
        success: function (response) {

            if(response){

                console.log(response);

                const instructions = JSON.parse(response);

                let itemsCt = 0;

                totalSlides = instructions.length;

                const instructionTabs = document.querySelectorAll('.instruction-steps .step');

                if(instructionTabs.length == 0) {
                    for(s=1;s<=totalSlides;s++){
                        const div = document.createElement('div');
                        div.id = 'step_'+s;
                        div.classList.add('step');
                        div.setAttribute("data-slide", s);

                        if(s == 1) {
                            div.classList.add('active'); 
                        }

                        div.addEventListener('click', (e) => {
                            switchInstructionsSlide(e.target.dataset.slide);
                        });

                        instructionSteps.appendChild(div);
                    }

                }

                const instructionContent = document.querySelectorAll('.instruction-text .content');

                    if(instructionTabs.length == 0) {

                        for(s=1;s<=totalSlides;s++){
                            const div = document.createElement('div');
                            div.id = 'content_'+s;
                            if(s == 1){
                                //div.style.display = 'block';
                                div.style.opacity = 1;
                            } else {
                                //div.style.display = 'none';
                                div.style.opacity = 0;
                            }
                            div.classList.add('content');
                            div.setAttribute("data-slide", s);
                            div.innerHTML = '<div class="text">'+instructions[s-1].desc+'</div><img src="'+instructions[s-1].img+'">';
                            instructionText.appendChild(div);
                        }

                    }                        
               
            }

        }
        
    });
    
        
}


function switchInstructionsSlide(slideNum) {

    console.log('change to slide: '+slideNum);

    instructionSlide = slideNum;

    const slideSet = document.querySelectorAll('.instruction-text .content');

    slideSet.forEach(slideItem => {
        if(slideNum == slideItem.dataset.slide) {
            //slideItem.style.display = 'block';
            slideItem.style.opacity = 1;
        } else {
            //slideItem.style.display = 'none';
            slideItem.style.opacity = 0;
        }
    });

    const slideNav = document.querySelectorAll('.instruction-steps .step');

    slideNav.forEach(slideTab => {
        if(slideNum == slideTab.dataset.slide) {
            slideTab.classList.add('active');
        } else {
            slideTab.classList.remove('active');
        }
    });

    if(slideNum!==1){
        backButton.style.display = 'block';
    } else {
        backButton.style.display = 'none';
    }
    if (slideNum===totalSlides){
        playButton.style.display = 'block';
        playButton.disabled = true;
        nextButton.style.display = 'none';
    } else {
        playButton.style.display = 'none';
        nextButton.style.display = 'block';
    }
   
}
*/