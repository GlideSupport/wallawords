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



helpButton.addEventListener('click', () => {
    //instructionSlide = 1;
    //startInstructions(instructionSlide);
    setTimeout(() => {
        const instructionEl = document.querySelector('#instruction-text');
        const instructionScreenEl = document.querySelector('#instruction-screen');

        if (instructionEl && instructionScreenEl) {
            instructionEl.style.height = "0px";
            const containerEl = document.querySelector('.instructions-container');
            const containerStyle = window.getComputedStyle(containerEl);
            const paddingBottom = parseInt(containerStyle.paddingBottom, 10) || 0;

            const elHeight = instructionScreenEl.offsetHeight - (containerEl.offsetHeight - paddingBottom);
            instructionEl.style.height = `${elHeight}px`;
        }
    }, 50);

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
    instructionScreen.style.display = 'none';

});

const startGameAgain = () => {
    document.body.style.overflow = 'auto';
    titleScreen.style.display = 'none';
    instructionScreen.style.display = 'none';
    instructionSlide = 1;

    finalScoreScreen.style.display = 'none';
    gameScreen.removeAttribute('style');
    gameRow.removeAttribute('style');
    document.body.classList.remove('final-result', 'final-result-faild');
    document.getElementById('resultFailed')?.remove();
    const hideElements = (...elementIds) => {
        elementIds.forEach(id => {
            const element = document.getElementById(id);
            if (element) element.style.display = 'none';
        });
    };
    hideElements('result-sentence-counter', 'kicker', 'puzzle-title');
    gameGridElement.innerHTML = `<span></span><img src="${localVars.site_url}/wp-content/themes/wallawords/assets/build/images/spinner.svg" class="loading"><span></span>`;
// Call hideElements with all the IDs you want to hide

    //switchInstructionsSlide(instructionSlide);
    startGame(puzzleCounter);
};

playButton?.addEventListener('click', startGameAgain);



document.addEventListener('DOMContentLoaded', function () {
  // Get the query parameters from the URL
  const urlParams = new URLSearchParams(window.location.search);

  // Check if 'start_puzzle' is true in the URL
  if (urlParams.get('start_puzzle') === 'true') {
    urlParams.delete('start_puzzle');
    const newUrl = urlParams.toString() ? `${window.location.pathname}?${urlParams}` : window.location.pathname;
    window.history.replaceState({}, '', newUrl);

    document.body.style.overflow = 'auto';
    titleScreen.style.display = 'none';
    instructionScreen.style.display = 'none';
    instructionSlide = 1;
    startGame(puzzleCounter);
  }
});
// --- Cookie helper functions ---
function setCookie(name, value, days) {
    let expires = "";
    if (days) {
        const date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}

function getCookie(name) {
    const nameEQ = name + "=";
    const ca = document.cookie.split(';');
    for(let i=0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

// --- Auto popup after 3 sec if cookie not set ---
if (!getCookie("popup_shown")) {
    setTimeout(() => {
        const instructionTextWrapper = document.getElementById('instruction-text');
        if (!instructionTextWrapper) return;

        let viewportHeight = window.visualViewport ? window.visualViewport.height : window.innerHeight;
        let offsetVal = 104;

        if (window.innerWidth > 747) {
            offsetVal = 164; // add 20px for larger size screens
        }

        let resetInstructionsHeight = viewportHeight - offsetVal;
        instructionTextWrapper.style.height = `${resetInstructionsHeight}px`;

        document.body.style.overflow = 'hidden';

        var helpScreen = jQuery('#instruction-screen-wrapper');

        if (!helpScreen.hasClass('active')) {
            helpScreen.fadeIn(500);
            helpScreen.addClass('active');
            jQuery('#instruction-screen-overlay').css('z-index','999');
        }

        // ✅ Mark popup as shown for 7 days
        setCookie("popup_shown", "yes", 7);

    }, 3000);
}