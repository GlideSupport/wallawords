<?php
/**
 * WallaWords Game specific functions
 */

 /**
 * add a game options page
 */
 if( function_exists('acf_add_options_page') ) {
    // Settings options page.
    $option_page = acf_add_options_page(array(
        'page_title'    => __('Game Settings'),
        'position'		=> 40,
        'menu_title'    => __('Game Settings'),
        'menu_slug'     => 'game-general-settings',
        'capability'    => 'edit_posts',
        'redirect'      => false
    ));
    
}


function wallawords_encrypt_data($data, $nonce) {
    $cipher = "AES-256-CBC";
    $ivlen  = openssl_cipher_iv_length($cipher);
    $iv     = openssl_random_pseudo_bytes($ivlen);

    // Use hashed nonce as key
    $encryption_key = hash('sha256', $nonce, true);

    $encrypted = openssl_encrypt($data, $cipher, $encryption_key, OPENSSL_RAW_DATA, $iv);

    // Encode iv + encrypted text as base64
    return base64_encode($iv . $encrypted);
}

 /**
 * Return all puzzle data from WP UI pages in JSON
 */

add_action("wp_ajax_wallawords_get_puzzle_data", "wallawords_get_puzzle_data");
add_action("wp_ajax_nopriv_wallawords_get_puzzle_data", "wallawords_get_puzzle_data");

function wallawords_get_puzzle_data() {
    // Ensure nonce is valid
    if ( !isset($_REQUEST['nonce']) || !wp_verify_nonce($_REQUEST['nonce'], 'ajax_nonce') ) {
        wp_send_json_error(['message' => 'Invalid nonce']);
        exit;
    }
    $today_puzzle = false;
    $is_acadamy = isset($_GET['is_acadamy']) ? array(intval($_GET['is_acadamy'])) : []; 
    $gameID = isset($_GET['gameID']) ? array(intval($_GET['gameID'])) : []; 
    $completed = isset($_GET['completed']) ? sanitize_text_field($_GET['completed']) : '';
    for($level =1; $level<= 3; $level++){
        $level_key = "select_level_".$level."_puzzle";
        $acadamyPluzzle[$level] = get_field($level_key ,'options');
    }
    
    if($is_acadamy){
        $level = $_GET['level'] ?? 1;
        $level_key = "select_level_".$level."_puzzle";
        $gameID[] = $acadamyPluzzle[$level];//get_field($level_key ,'options');
    }else{
        $today_puzzle = true;
    }

    // Prepare base query arguments
    $get_puzzle_args = [
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'post_type'      => 'puzzle',
        'orderby'        => 'date',
    ];
  
    if ($gameID) {
        $get_puzzle_args['post__in'] = $gameID;
        $get_puzzle_args['orderby'] = 'post__in';
    }
   
    if($today_puzzle){
        $get_puzzle_args['posts_per_page'] = 1;
        if (!$gameID) {
            $get_puzzle_args['orderby'] = 'date';
            $get_puzzle_args['order'] = 'DESC';
        }
    }else{
        // Get gameID and completed status if present
       

        // Handle the "completed" parameter
        if ($completed !== '') {
            $completed = stristr($completed, ',') ? explode(',', $completed) : [$completed];
            $gameID = array_merge($gameID, $completed);
            $get_puzzle_args['post__not_in'] = $gameID;
        }
    }
   
    // echo '<pre>';
    // print_r($get_puzzle_args);
    // Fetch puzzles based on the prepared arguments



    $get_puzzle_posts = new WP_Query($get_puzzle_args);

    // If no posts found, fallback to retrieving all puzzles
    if ($get_puzzle_posts->found_posts == 0) {
        $get_puzzle_posts = new WP_Query([
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'post_type'      => 'puzzle',
            'orderby'        => 'date',
        ]);
    }

    if ($get_puzzle_posts->have_posts()) {
        $output_data = [];

        while ($get_puzzle_posts->have_posts()) {
            $get_puzzle_posts->the_post();
            $puzzle_post_meta = get_post_meta(get_the_ID());
            // Gather puzzle data
            $default_prompt = get_field('wwp_default_prompt','options') ?? '';
            $game_prompt = get_field('wwp_prompt', get_the_ID());
            $output_data[] = [
                'id'          => get_the_ID(),
                'title'       => get_the_title(),
                'fullPoem'    => $puzzle_post_meta['full_poem'][0] ?? '',
                'prompt'      => !empty($game_prompt) ? $game_prompt : $default_prompt,
                'health'      => get_field('wwp_health', get_the_ID()) ?? 7,
                'correctWords' => isset($puzzle_post_meta['correct_words']) ? json_decode($puzzle_post_meta['correct_words'][0]) : [],
                'sentences'   => isset($puzzle_post_meta['sentences']) ? json_decode($puzzle_post_meta['sentences'][0]) : [],
                'lockedWords' => isset($puzzle_post_meta['locked_words']) ? array_map('intval', json_decode($puzzle_post_meta['locked_words'][0])) : [],
                'incorrectWords' => isset($puzzle_post_meta['incorrect_words']) ? json_decode($puzzle_post_meta['incorrect_words'][0]) : [],
                'columns'     => isset($puzzle_post_meta['columns']) ? array_values(json_decode($puzzle_post_meta['columns'][0])) : [],
            ];
        }

        // Optionally shuffle if no gameID filter was provided
        if (!isset($_GET['gameID'])) {
            shuffle($output_data);
        }

        // --- Encryption Section ---
        $encrypted_data_with_iv = encrypt_puzzle_data($output_data, $_REQUEST['nonce']);

        wp_send_json_success([$encrypted_data_with_iv]);
    } else {
        wp_send_json_error(['message' => 'No puzzles found']);
    }
}

/**
 * Encrypt puzzle data with AES-256-CBC encryption
 *
 * @param array $data
 * @param string $nonce
 * @return string
 */
function encrypt_puzzle_data($data, $nonce) {
    $encryption_key = hash('sha256', $nonce, true); // 32-byte key
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));

    $encrypted_data = openssl_encrypt(
        json_encode($data),
        'aes-256-cbc',
        $encryption_key,
        OPENSSL_RAW_DATA,
        $iv
    );

    // Combine IV + encrypted data, then base64 encode
    return base64_encode($iv . $encrypted_data);
}



function wallawords_get_instruction_data() {

    if(get_field('ww_game_instructions','options')):
        //print_r(get_field('ww_game_instructions','options'));

        $instructions = get_field('ww_game_instructions','options');

        $instructionsArr = array();

        foreach($instructions as $key => $step):
            $instructionsArr[$key]['desc'] = $step['step_description'];
            $instructionsArr[$key]['img'] = $step['step_image']['url'];
        endforeach;

        if(count($instructionsArr) > 0):
            $instructionsArr = json_encode($instructionsArr,JSON_HEX_APOS);
            echo $instructionsArr;
            exit;
        endif;

    endif;

}
add_action("wp_ajax_wallawords_get_instruction_data", "wallawords_get_instruction_data");
add_action("wp_ajax_nopriv_wallawords_get_instruction_data", "wallawords_get_instruction_data");


 /**
 * Game Admin UI
 */

add_action('admin_head', 'admin_custom_outputs');

function admin_custom_outputs() {
  echo '<script>
  jQuery(document).ready(function(){
    jQuery(".readonly").find("input").prop("readonly", true);
	jQuery(".readonly").find("textarea").prop("readonly", true);
	jQuery(".readonly").find("select").attr("disabled", true);
	});
</script>';
}

function enqueue_custom_admin_scripts($hook) {
    global $post;

    // Load only for the edit screen of the 'puzzle' post type
    if ($post->post_type === 'puzzle' && in_array($hook, ['post-new.php', 'post.php'])) {
        wp_enqueue_script(
            'game-admin-scripts', // Handle
            get_bloginfo('template_directory') . '/includes/game_admin_js.js', // Path to the JS file
            'jquery', // Dependencies
            true // Load in footer
        );

        wp_enqueue_style(
            'game-admin-css', // Handle
            get_bloginfo('template_directory') . '/includes/game_admin_style.css', // Path to the JS file
            //'jquery', // Dependencies
            //true // Load in footer
        );

        //sortable script for drag/drop
        wp_enqueue_script(
            'game-admin-sortable', // Handle
            get_bloginfo('template_directory') . '/assets/build/game/sortable.min.js', // Path to the JS file
            'jquery', // Dependencies
            true // Load in footer
        );

    }
}
add_action('admin_enqueue_scripts', 'enqueue_custom_admin_scripts');

// Add custom form directly below the title
function render_custom_puzzle_form($post) {
    // Ensure this is only applied to the 'puzzle' custom post type
    if ($post->post_type !== 'puzzle') {
        return;
    }  

    // Use nonce for verification
    wp_nonce_field('save_custom_puzzle_data', 'custom_puzzle_nonce');

        /*
    echo '<pre>';
    print_r(get_post_meta($post->ID));
    echo '</pre>';
    */

    // Get existing values (if any)
    $full_poem = get_post_meta($post->ID, 'full_poem', true);

    $words = array();

    $sentences = array();

    if($full_poem):
        $full_poem = iconv('UTF-8', 'ASCII//TRANSLIT', $full_poem); //add a filter for MS fancy quotes/apostrophes
        $words = explode(' ', $full_poem); // Split the full poem into words
    endif;

    $output = '<div style="margin: 20px 0; padding: 10px 0; border-top: 1px solid #ddd; border-bottom: 1px solid #ddd;">';

    //nav
    //$output .= '<div class="puzzle-admin-nav"><a href="#">Step 1</a> > <a href="#">Step 2</a> > <a href="#">Step 3</a></div>';

    //difficulty
    $rank = getPuzzleDifficultyRating($post->ID);

    if(isset($rank)):
        $output .= '<div class="difficulty"><h3>Difficulty Rating: ['.str_replace('difficulty_','',$rank[0]).' - '.$rank[1].' moves]</h3></div>';
    endif;

    //step 1
    $output .= '<div id="tab-1" class="puzzle-tab">
    <h2>Enter Full Poem</h2>
    <textarea id="full-poem" name="full_poem" cols="100" rows="2">'.$full_poem.'</textarea>
    <p id="word-count">Word Count: 0</p>';

    //$output .= get_field($post->ID,'full_poem_directions');
    
    $has_correct_words = get_post_meta($post->ID, 'correct_words', true);

    if(!$has_correct_words || (is_array($has_correct_words) && count($has_correct_words == 0))):
        $output .= '<p><a id="generate-puzzle" class="button button-primary button-large"><i class="dashicons dashicons-image-rotate"></i> Generate Game Grid &raquo;</a></p>';
    else:
        $output .= '<p><a id="generate-puzzle" class="button button-primary button-large"><i class="dashicons dashicons-image-rotate"></i> Reset Grid &raquo;</a></p>';
    endif;

    $output .= '</div>';

    $has_locked_words = get_post_meta($post->ID, 'locked_words', true);

    $showBlock = 'none';
    $showRand = 'none';

    if($has_correct_words):
        $showBlock = 'inline-block';      
    endif;  

    if($has_locked_words):
        $showRand = 'inline-block';      
    endif;  

    $output .= '<div id="tab-2" class="puzzle-tab" style="display:'.$showBlock.';">
    <h2>Configure Game Board</h2>
    <p style="color:#c00;">Select at least 2 tiles to lock</p>';    
    $output .= '<p><a id="randomize" class="button button-primary button-large" style="display:'.$showRand.';">Randomize <i class="dashicons dashicons-randomize"></i></a></p>';
    $output .= '<div class="game-grid">';
    if($full_poem):
        $output .= output_game_grid_for_admin($post->ID,$full_poem);
    endif;
    $output .= '</div>';
    $output .= '</div>';    
    $output .= '</div>';

    echo $output;

}

add_action('edit_form_after_editor', 'render_custom_puzzle_form');

function output_game_grid_for_admin($post_id = '',$full_poem = '') {

    $fullReset = 0;

    if(isset($_GET['postID'])):
        $post_id = $_GET['postID'];
    endif;

    if(isset($_GET['fullPoem'])):
        $full_poem = trim($_GET['fullPoem']);
    endif;

    if(isset($_GET['fullReset'])):
        $fullReset = $_GET['fullReset'];
    endif;    

    $words = array();

    if($full_poem):
        $full_poem = iconv('UTF-8', 'ASCII//TRANSLIT', $full_poem); //add a filter for MS fancy quotes/apostrophes
        //$words = explode(' ', $full_poem); // Split the full poem into words      
        $words = explode(' ', strtolower($full_poem)); // Split the full poem into lowercase words  
    endif;

    $has_correct_words = get_post_meta($post_id, 'correct_words', true);
    $output = '';
    foreach($words as $key => $word):
        $word = iconv('UTF-8', 'ASCII//TRANSLIT', $word); //add a filter for MS fancy quotes/apostrophes
        $output .= '<input name="correct_' . $key . '" value="' . $word . '" type="hidden" class="correct">';
    endforeach;

    $locked_words = '';

    if($fullReset == 0):
        $has_incorrect_words = get_post_meta($post_id, 'incorrect_words', true);

        if($has_incorrect_words):
            $incorrect_words = json_decode($has_incorrect_words);
        endif; 

        $has_locked_words = get_post_meta($post_id, 'locked_words', true);
        
        if($has_locked_words):
            $locked_words = json_decode($has_locked_words);    
        endif;  

    endif;

    $incorrectCt = 0;

        for($key=0;$key<15;$key++):
            $value = '';            
            $class = '';
            $checked = '';
            if($incorrect_words && count($incorrect_words) > 0):
                $value = $incorrect_words[$incorrectCt];
            else:
                $value = $words[$key];
                $value = iconv('UTF-8', 'ASCII//TRANSLIT', $value); //add a filter for MS fancy quotes/apostrophes
            endif;

            if(is_array($locked_words) && in_array($key,$locked_words)):
                $class = ' locked';    
                $checked = ' checked';  
                $value = $words[$key];
                $value = iconv('UTF-8', 'ASCII//TRANSLIT', $value); //add a filter for MS fancy quotes/apostrophes
            else:
                $incorrectCt++;
            endif;

            $output .= '<div class="block '.$class.'">
                <div class="handle"><span class="dashicons dashicons-move"></span></div>
                <label for="locked_'.$key.'">
                <input id="incorrect_'.$key.'" name="incorrect_'.$key.'" type="text" value="'.$value.'" class="readonly" readonly><input type="checkbox" id="locked_'.$key.'" name="locked_'.$key.'" value="'.$key.'"'.$checked.'>
                </label>
            </div>';
            endfor;

    if(isset($_GET['isAjax'])):
        echo $output; 
        exit;
    else:
        return $output;
    endif;
    
}

add_action("wp_ajax_output_game_grid_for_admin", "output_game_grid_for_admin");
add_action("wp_ajax_nopriv_output_game_grid_for_admin", "output_game_grid_for_admin");

// Save custom form data
function save_custom_puzzle_data($post_id) {
    // Check nonce
    if (!isset($_POST['custom_puzzle_nonce']) || !wp_verify_nonce($_POST['custom_puzzle_nonce'], 'save_custom_puzzle_data')) {
        return;
    }

    // Check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check user permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save data
    if (isset($_POST['full_poem'])) {

        update_post_meta($post_id, 'full_poem', sanitize_text_field($_POST['full_poem']));

        $sentences = preg_split('/(?<=[.!?])\s*/', $_POST['full_poem']);
    
        $sentence_values = array();
    
        $word_offset = 0; // To track the starting word index in the full poem

        //check for blanks
        foreach ($sentences as $key => $sentence) {
            if($sentence == ''):
                unset($sentences[$key]);
            endif;
        }
    
        //reloop for counts
        foreach ($sentences as $sentence) {
            // Split sentence into words
            $sentence_words = explode(' ', $sentence);
    
            // Find the start index of the sentence in the full text
            $sentence_start = $word_offset;
    
            // Update the word offset by the number of words in this sentence
            $word_offset += count($sentence_words);
    
            // The end index of the sentence is just the last word index for this sentence
            $sentence_end = $word_offset - 1;
    
            // Store the sentence positions (start and end indices)
            if($sentence != ''):
                $sentence_values[] = array($sentence_start,$sentence_end);
            endif;
        
        }

        /*
        // Output the sentence start and end positions
        echo "Sentence positions:\n";
        print_r($sentence_values);

        // Output the results
        echo "Sentences: \n";
        print_r(json_encode($sentences));
        exit;
        */
        
        update_post_meta($post_id, 'sentences', json_encode($sentence_values,JSON_HEX_APOS));

        update_post_meta($post_id, 'columns', json_encode($sentences,JSON_HEX_APOS));

        update_post_meta($post_id, 'difficulty', '');

        // $words = explode(' ', $full_poem); // Split the full poem into words

        $correct_words = array();
        $incorrect_words = array();
        $locked_words = array();

        for($ct = 0; $ct < 15; $ct++):

            if($_POST['correct_'.$ct]):
                $correct_words[] = $_POST['correct_'.$ct];
            endif;            

            if(!isset($_POST['locked_'.$ct]) && $_POST['incorrect_'.$ct]):
                $incorrect_words[] = $_POST['incorrect_'.$ct];
            endif;

            if(isset($_POST['locked_'.$ct])):
                $locked_words[] = $_POST['locked_'.$ct];
            endif;

        endfor;
        
        update_post_meta($post_id, 'correct_words', json_encode($correct_words,JSON_HEX_APOS));
        
        if(count($incorrect_words) > 0):
            update_post_meta($post_id, 'incorrect_words', json_encode($incorrect_words,JSON_HEX_APOS));
        endif;

        if(count($locked_words) > 0):
            update_post_meta($post_id, 'locked_words', json_encode($locked_words));
        endif;

        //set difficulty level
        $levels = get_field('difficulty_settings','options');


   
    }
}
add_action('save_post', 'save_custom_puzzle_data');


//php version of our graphic calc
function calculateMinSwapsUsingGraphMethod($solvedState, $currentState) {
    $n = count($currentState);

    // Create an array of pairs where the first element is the current word
    // and the second element is the index it should be in the solved state
    $arrPos = [];
    for ($i = 0; $i < $n; $i++) {
        $arrPos[] = [$currentState[$i], $i];
    }

    // Sort the array by the index of the word in the solved state
    usort($arrPos, function($a, $b) use ($solvedState) {
        return array_search($a[0], $solvedState) - array_search($b[0], $solvedState);
    });

    // Initialize visited array
    $visited = array_fill(0, $n, false);
    $ans = 0;

    // Traverse the array elements
    for ($i = 0; $i < $n; $i++) {
        // Skip already visited elements or those in the correct position
        if ($visited[$i] || $arrPos[$i][1] == $i) {
            continue;
        }

        // Find out the number of nodes in this cycle
        $cycleSize = 0;
        $j = $i;

        while (!$visited[$j]) {
            $visited[$j] = true;

            // Move to the next node in the cycle
            $j = $arrPos[$j][1];
            $cycleSize++;
        }

        // Update answer by adding current cycle's swap count
        if ($cycleSize > 0) {
            $ans += $cycleSize - 1;
        }
    }

    return $ans;
}

function getPuzzleDifficultyRating($puzzle_id)
{
    $full_poem = get_post_meta($puzzle_id, 'full_poem', true);
    $has_correct_words = get_post_meta($puzzle_id, 'correct_words', true);
    if ($has_correct_words):

        $words = array();

        if ($full_poem):
            $full_poem = iconv('UTF-8', 'ASCII//TRANSLIT', $full_poem); //add a filter for MS fancy quotes/apostrophes
            $words = explode(' ', $full_poem); // Split the full poem into words
        endif;
        $solved_state = json_decode($has_correct_words);
        $has_incorrect_words = get_post_meta($puzzle_id, 'incorrect_words', true);
        if ($has_incorrect_words):
            $incorrect_words = json_decode($has_incorrect_words);
        endif;
        $has_locked_words = get_post_meta($puzzle_id, 'locked_words', true);

        if ($has_locked_words):
            $locked_words = json_decode($has_locked_words);
        endif;
        $current_state = array();
        $incorrectCt = 0;
        for ($key = 0; $key < 15; $key++):
            $value = '';
            if ($incorrect_words && count($incorrect_words) > 0):
                $value = $incorrect_words[$incorrectCt];
            endif;
            if (is_array($locked_words) && in_array($key, $locked_words)):
                $value = $words[$key];
                $value = iconv('UTF-8', 'ASCII//TRANSLIT', $value); //add a filter for MS fancy quotes/apostrophes
            else:
                $incorrectCt++;
            endif;
            $current_state[] = $value;
        endfor;
        $this_difficulty = calculateMinSwapsUsingGraphMethod($solved_state, $current_state);

        $levels = get_field('difficulty_settings', 'options');
        if (isset($levels) && is_array($levels)):
            foreach ($levels as $key => $difficulty):
                if ($difficulty <= $this_difficulty):
                    $rank = $key;
                endif;
            endforeach;
            return array($rank, $this_difficulty);
        endif;
    endif;
}