<?php
/**
 * Template Name: Game
 * Template Post Type: page
 *
 * This template is for displaying the WallaWords game.
 *
 * @link https://developer.wordpress.org/themes/template-files-section/page-template-files/
 *
 * @package Base Theme Package
 * @since 1.0.0
 */

// Include header.
get_header();

BaseTheme::enqueue_script('assets/build/game/wallawords-game.js', array( 'jquery' ), filemtime(__DIR__. 'assets/build/game/wallawords-game.js'));
BaseTheme::enqueue_script('assets/build/game/sortable.min.js', array( 'jquery' ), filemtime(__DIR__. 'assets/build/game/sortable.min.js'));
BaseTheme::enqueue_script('assets/build/game/confetti.min.js', array( 'jquery' ), filemtime(__DIR__. 'assets/build/game/confetti.min.js'));

/*
BaseTheme::enqueue_script('assets/build/game/wallawords-game.js', 
array( 'jquery' ), 
array(
    'in_footer' => true,
    'strategy'  => 'defer',
));

BaseTheme::enqueue_script('assets/build/game/sortable.min.js', 
array( 'jquery' ), 
array(
    'in_footer' => true,
    'strategy'  => 'defer',
));

BaseTheme::enqueue_script('assets/build/game/confetti.min.js', 
array( 'jquery' ), 
array(
    'in_footer' => true,
    'strategy'  => 'defer',
));
*/
?>

<section id="page-section" class="page-section">

    <div role="game" class="game-pieces-animated">

        <div class="piece piece-am"></div>
        <div class="piece piece-I"></div>
        <div class="piece piece-so"></div>
        <div class="piece piece-be"></div> 

        <div class="piece piece-think"></div>
        <div class="piece piece-joy"></div>

        <div class="piece piece-must"></div>

        <div class="piece piece-dreams"></div>

    </div>

	<!-- Content Start -->
	<div class="game-wrapper">

    <!-- Title Screen -->
    <div id="title-screen" class="title-screen">
        <h1>Welcome to<br> Walla<br><span class="">Words</span></h1>
        <p>A Walla is a puzzle and a poem.<br> Solve it to find meaning in two directions.</p>
        <div class="start-buttons">
            <!-- <a id="play-game" class="site-btn" role="button" aria-label="Let's Begin the Game">Let's Begin</a> -->
            <a id="difficulty-play-game" class="site-btn" role="button" aria-label="Let's Begin the Game">Let's Begin</a>
        </div>
    </div>

    <!-- Difficulty Popup -->
    
     <div id="difficulty-popup" class="difficulty-popup  level-finish-popup" style="display: none;">
        <?php 
        $classic_difficulty = get_field('classic_difficulty','options');
        $pro_difficulty = get_field('pro_difficulty','options');
        $genius_difficulty = get_field('genius_difficulty','options');

        
        $classic_difficulty_icon = get_field('classic_difficulty','options')['icon'] != '' ? get_field('classic_difficulty','options')['icon'] : get_template_directory_uri() .'/assets/src/images/classic-level.png';
        $pro_difficulty_icon = get_field('pro_difficulty','options')['icon'] != '' ? get_field('pro_difficulty','options')['icon'] : get_template_directory_uri() .'/assets/src/images/pro-level.png';
        $genius_difficulty_icon = get_field('genius_difficulty','options')['icon'] != '' ? get_field('genius_difficulty','options')['icon'] : get_template_directory_uri() .'/assets/src/images/genius-level.png';


        ?>
        <div class="popup-card">
                <div class="piece piece-one blur"></div>
                <div class="piece piece-two blur"></div>
                <div class="piece piece-three blur"></div>
                <div class="piece piece-four blur"></div>
                <div id="level-badge" class="content-area level-1">
                    <div class="badge-box">
                        <div class="hexagone-icon">
                            <div class="icon"><img src="<?php echo $classic_difficulty_icon ?>"></div>
                        </div>
                    </div>
                    <div class="arrows"></div>

                    <div class="popuptitle heading-1">Select <span>Difficulty</span></div>
                    
                    <div class="level-tabs">
                        <div class="tab tab-1 active" data-level="classic" data-levelnumber="1" data-message="<?php echo $classic_difficulty['message'] ?>" data-message_tootip="<?php echo $classic_difficulty['message_tootip'] ?>">
                            <img src="<?php echo $classic_difficulty_icon ?>">
                            <?php echo $classic_difficulty['label'] != '' ? $classic_difficulty['label'] : 'Classic' ?>
                        </div>
                     
                        <div class="tab tab-2" data-level="pro" data-levelnumber="2" data-message="<?php echo $pro_difficulty['message'] ?>" data-message_tootip="<?php echo $pro_difficulty['message_tootip'] ?>">
                            <img src="<?php echo $pro_difficulty_icon ?>"> 
                            <?php echo $pro_difficulty['label'] != '' ? $pro_difficulty['label'] : 'Pro' ?>
                        </div>
                        <div class="tab tab-3" data-level="genius" data-levelnumber="3" data-message="<?php echo $genius_difficulty['message'] ?>" data-message_tootip="<?php echo $genius_difficulty['message_tootip'] ?>">
                            <img src="<?php echo $genius_difficulty_icon ?>"> 
                            <?php echo $genius_difficulty['label'] != '' ? $genius_difficulty['label'] : 'Genius' ?>
                        </div>
                    </div>
                    <div class="popupcontent level-content"> <?php echo html_entity_decode($classic_difficulty['message']) ?></div>
                    <a class="site-btn" id="play-game" role="button" aria-label="Next Level">Play</a>
                  
                </div>
            </div>
     </div>  
    <!-- Game Screen -->
    <div id="game-screen" class="wrapper" style="display: none;">
        <div class="game-container">
            <!-- Row 1: Move Counter and Poem Display -->
            <div class="game-stats">
                <div class="move-container">
                    <div id="kicker" class="p4" style="display: none;">Walla #2:</div>
                    <div id="puzzle-title" class="heading-5" style="display: none;"></div>
                    <div class="flex-container"> 
                        <div id="move-counter" class="move-counter  p1"></div>
                        <div id="sentence-counter" class="p1"></div>
                    </div>
                    <div class="result-bar">
                        <div id="result-sentence-counter" class="p1" style="display: none;"></div>
                        <div class="health-bar" id="health-bar" style="display: none;"></div>
                    </div>
                </div>

                <div id="sentence-list-container" class="sentence-list-container">
                
                <div class="close-button" id="close-sentences-button">
					<svg role="presentation" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M4.10745 15.8925C3.67288 15.458 3.65698 14.7717 4.07189 14.3568L14.3568 4.07187C14.7717 3.65697 15.458 3.67286 15.8926 4.10743C16.3271 4.54201 16.343 5.22832 15.9281 5.64322L5.64324 15.9281C5.22833 16.343 4.54203 16.3271 4.10745 15.8925Z" fill="white"/>
					<path d="M4.10745 4.10745C4.54203 3.67288 5.22833 3.65698 5.64324 4.07189L15.9281 14.3568C16.343 14.7717 16.3271 15.458 15.8926 15.8926C15.458 16.3271 14.7717 16.343 14.3568 15.9281L4.07189 5.64324C3.65699 5.22833 3.67288 4.54203 4.10745 4.10745Z" fill="white"/>
					</svg>
				</div>

                <div id="sentence-list-text"></div>

                </div>
            </div>
            <!-- Row 2: Grid of Tiles -->
            <div id="game-row" class="game-row">
                <div id="sortable-grid" class="game-grid"></div>
                <div id="game-prompt" class="game-prompt"></div>
                <a id="back-to-result" class="site-btn"  role="button" aria-label="Back to puzzle" style="display: none;">Back To Result</a>
            </div>
            <!-- sentence-list-container -->
            <div id="final-score-screen" class="final-score-screen sentence-list-container final-result" style="display: none;">
                <ol></ol>
                <div class="finish-buttons">
                    <a id="back-to-puzzle" class="site-btn"  role="button" aria-label="Back to puzzle">Back To Puzzle</a>
                    <a id="share-button" class="site-btn" role="button" aria-label="Share game results">Share Results</a>
                </div>
            </div>
            
        </div>
    </div>

    <div id="final-puzzle-acadamy-popup" class="level-finish-popup" style="display: none;">
        <div class="popup-card">
            <div class="piece piece-one"></div>
            <div class="piece piece-two"></div>
            <div class="piece piece-three"></div>
            <div class="piece piece-four"></div>
            <div  id="level-badge"  class="content-area  level-1">
            <div class="badge-box">           
                <div class="hexagone-txt">
                <div class="p4">Level</div>
                <div class="heading-5" id="level-done">1/3</div>
                </div>
            </div>
             <div class="arrows"></div>
            <div class="popuptitle heading-1">Well <span>done</span></div>
            <div class="popupcontent">You’ve completed Level 1! <br>Get ready for Level 2 and challenge yourself even more.</div>
            <a class="site-btn" id="next-level" role="button" aria-label="Next Level">Next Level</a>
            <div class="note">Remember to limit moves and avoid mistakesto get the highest score!</div>
            </div>
        </div>
    </div>

    <!-- Final Score Screen -->
    <!-- <div id="final-score-screen" class="final-score-screen" style="display: none;">

        <div class="score-header">
            <div id="final-move-count">
                0
            </div>

            <div id="sentence-toggle" class="sentence-toggle">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
                    <path d="M6.06667 11.9776C6.32917 12.0639 6.61111 11.8845 6.61111 11.6257V3.05749C6.61111 2.96213 6.57222 2.86678 6.48958 2.80775C6.01319 2.45358 4.91944 1.99951 3.5 1.99951C2.27257 1.99951 1.12535 2.30147 0.439931 2.54666C0.165278 2.64656 0 2.90083 0 3.17554V11.5826C0 11.8528 0.311111 12.0412 0.585764 11.9572C1.35139 11.7188 2.56424 11.4441 3.5 11.4441C4.32396 11.4441 5.42014 11.7619 6.06667 11.9776ZM7.93333 11.9776C8.57986 11.7619 9.67604 11.4441 10.5 11.4441C11.4358 11.4441 12.6486 11.7188 13.4142 11.9572C13.6889 12.0435 14 11.8528 14 11.5826V3.17554C14 2.90083 13.8347 2.64656 13.5601 2.54893C12.8747 2.30147 11.7274 1.99951 10.5 1.99951C9.08055 1.99951 7.98681 2.45358 7.51042 2.80775C7.43021 2.86678 7.38889 2.96213 7.38889 3.05749V11.6257C7.38889 11.8845 7.67326 12.0639 7.93333 11.9776Z" fill="white"/>
                </svg>
            </div>
        </div>

        <div class="final-page-toggle" id="final-page-toggle">
            <div class="toggle active">Results</div>
            <div class="toggle">Review</div>
        </div>

       <p class="medium-text">How does your score stack up?</p>
       
        <div class="final-poem" id="final-poem"></div>
        
        <?php if(get_field('score_rankings','options')):            
            $rankings = get_field('score_rankings','options');
        ?>

        <div class="score-table">
        <?php foreach($rankings as $rkey => $rank):?>
            <div id="rank_<?php echo $rkey;?>" class="score-row" data-from="<?php echo $rank['score_range_from'];?>" data-to="<?php echo $rank['score_range_to'];?>">
                <img src="<?php echo $rank['icon'];?>" alt="<?php echo $rank['label'];?>" class="score-icon">            
                <span class="score-title"><?php echo $rank['label'];?></span>
                <span class="score-moves"><?php echo $rank['score_range_from'];?> to <?php echo $rank['score_range_to'];?>  moves</span>
            </div>    
        <?php endforeach;?>
        </div>
        <?php endif;?>

        <div class="finish-buttons">
            <a id="share-button" class="site-btn" role="button" aria-label="Share game results">Share Results</a>
            <a id="play-again" class="site-btn" role="button" aria-label="Play another game">Play another!</a>
        </div>
    </div> -->

    <?php if(is_single() && get_post_type() == "puzzle"): ?>
        <input type="hidden" id="puzzle_id" value="<?php echo get_the_ID();?>">

        <?php /*
        <div id="share-modal">

            <div class="modal-content">
                <a class="share-close">X</a>

                <p>Share your game results with a friend!</p>
                
                <input type="text" id="share-phone" placeholder="Enter phone number">
                <textarea id="share-message"></textarea>
                <button class="share-send">Share &raquo;</button>
                
            </div>

        </div>
        */ ?>

    <?php endif; ?>

		<!-- Content End -->
	</div>
</section>

<?php
get_footer();
