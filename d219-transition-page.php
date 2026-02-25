<?php
/**
 * Plugin Name: District 219 Transition Page
 * Plugin URI: https://github.com/cameronsuorsa/d219-transition-page
 * Description: Creates a /transition page for District 219 Toastmasters transition information.
 * Version: 1.3.5
 * Author: District 219 Transition Committee
 * License: GPL v2 or later
 * GitHub Plugin URI: cameronsuorsa/d219-transition-page
 */

if (!defined('ABSPATH')) exit;

// Prevent fatal errors if file is loaded twice during activation/update
if (defined('D219_TRANSITION_VERSION')) return;

// =============================================================================
// CONFIGURATION
// =============================================================================

define('D219_SHOW_BANNER', true);
define('D219_ZOOM_LINK', 'https://us02web.zoom.us/j/84094774161'); // Town Hall Q&A Zoom link

// =============================================================================
// PLUGIN CONSTANTS
// =============================================================================

define('D219_TRANSITION_VERSION', '1.3.5');
define('D219_TRANSITION_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('D219_TRANSITION_PLUGIN_URL', plugin_dir_url(__FILE__));
define('D219_TRANSITION_PLUGIN_FILE', __FILE__);

define('D219_ASSETS_URL', D219_TRANSITION_PLUGIN_URL . 'assets/');
define('D219_ASSETS_DIR', D219_TRANSITION_PLUGIN_DIR . 'assets/');

// =============================================================================
// COMMITTEE DATA
// =============================================================================

function d219_get_committees() {
    return array(
        'alignment' => array(
            'title' => 'District Alignment Committee Chair',
            'title_short' => 'Alignment Committee',
            'name' => 'Dave Wiley, DTM, PDD',
            'email' => 'davewiley2018@gmail.com',
            'linkedin' => 'https://www.linkedin.com/in/david-wiley-9a889519/',
            'photo' => 'alignment-chair.webp',
            'desc_short' => 'Planning new Areas and Divisions',
            'desc_full' => 'This committee is composed of current Division Directors or their designates from both Districts 10 & 13. Together, they will create a plan for grouping clubs into new areas and divisions within the District.'
        ),
        'leadership' => array(
            'title' => 'District Leadership Committee Chair',
            'title_short' => 'Leadership Committee',
            'name' => 'Melissa McGavick, DTM, PRA, PID',
            'email' => 'district219dlc1@gmail.com',
            'linkedin' => 'https://www.linkedin.com/in/melissamcgavick/',
            'photo' => 'leadership-chair.webp',
            'desc_short' => 'Recruiting and nominating leaders',
            'desc_full' => 'This committee is composed of one member from each Division in both Districts 10 & 13. Together, they will seek potential candidates for District leadership roles, interview and assess the candidates, and present a slate of nominated candidates for election.'
        ),
        'transition' => array(
            'title' => 'District Transition Committee Chair',
            'title_short' => 'Transition Committee',
            'name' => 'Jing Humphreys, DTM, PDD',
            'email' => 'dtmjing@gmail.com',
            'linkedin' => 'https://www.linkedin.com/in/jing-humphreys-dtm2/',
            'photo' => 'transition-chair.webp',
            'desc_short' => 'Best practices and recommendations',
            'desc_full' => 'This committee is composed of Ad Hoc members from both Districts 10 & 13 with specific knowledge and expertise. The committee will create and present a report on District assets, best practices, traditions, and recommendations, equipping them to lead the new District effectively.'
        ),
        'group' => array(
            'title' => 'Group Transition Chair',
            'name' => 'Rhonda Mauer, DTM, PRA',
            'linkedin' => 'https://www.linkedin.com/in/rhondamauer/',
            'photo' => 'group-chair.webp'
        )
    );
}

// =============================================================================
// SLIDES AUTO-DETECTION
// =============================================================================

function d219_get_slides() {
    $slides_dir = D219_ASSETS_DIR . 'slides/';
    $slides_url = D219_ASSETS_URL . 'slides/';
    $slides = array();
    
    if (!is_dir($slides_dir)) return $slides;
    
    $files = scandir($slides_dir);
    foreach ($files as $file) {
        if (!preg_match('/\.(?:webp|jpg|jpeg|png|gif)$/i', $file)) continue;
        
        $number = null;
        $name = '';
        $basename = preg_replace('/\.(?:webp|jpg|jpeg|png|gif)$/i', '', $file);
        
        // Pattern 1: "_Page_XX" format (PowerPoint/PDF export)
        if (preg_match('/^(.*)_Page_(\d+)$/i', $basename, $m)) {
            $name = $m[1];
            $number = intval($m[2]);
        }
        // Pattern 2: Number at START (01-name, 01_name, 01name)
        elseif (preg_match('/^(\d+)[-_]?(.*)$/', $basename, $m)) {
            $number = intval($m[1]);
            $name = $m[2];
        }
        // Pattern 3: Number at END (name-01, name_01, Slide1)
        elseif (preg_match('/^(.*)[-_]?(\d+)$/', $basename, $m)) {
            $name = $m[1];
            $number = intval($m[2]);
        }
        
        if ($number !== null) {
            $name = trim($name, '-_ ');
            $name = $name ? ucwords(str_replace(array('-', '_'), ' ', $name)) : "Slide $number";
            $slides[$number] = array(
                'src' => $slides_url . $file,
                'title' => $name,
                'file' => $file,
                'number' => $number
            );
        }
    }
    
    ksort($slides);
    return array_values($slides);
}

/**
 * Get PDF file from slides folder if it exists
 */
function d219_get_slides_pdf() {
    $slides_dir = D219_ASSETS_DIR . 'slides/';
    $slides_url = D219_ASSETS_URL . 'slides/';
    
    if (!is_dir($slides_dir)) return null;
    
    $files = scandir($slides_dir);
    foreach ($files as $file) {
        if (preg_match('/\.pdf$/i', $file)) {
            return array(
                'url' => $slides_url . $file,
                'filename' => $file
            );
        }
    }
    
    return null;
}

/**
 * Get nomination form PDFs
 */
function d219_get_nomination_forms() {
    $forms_dir = D219_ASSETS_DIR . 'forms/';
    $forms_url = D219_ASSETS_URL . 'forms/';
    
    if (!is_dir($forms_dir)) return array();
    
    // Define forms with friendly names
    $form_definitions = array(
        '450c-district-leader-nominating-form.pdf' => 'District Leader Nominating Form (450C)',
        '450e-candidate-application.pdf' => 'Candidate Application (450E)',
        '450h-district-officer-biographical-info.pdf' => 'District Officer Biographical Info (450H)',
        '450D-district-leader-agreement-statement-ff.pdf' => 'District Leader Agreement & Release Statement (450D)',
    );
    
    $forms = array();
    foreach ($form_definitions as $filename => $title) {
        $filepath = $forms_dir . $filename;
        if (file_exists($filepath)) {
            $forms[] = array(
                'url' => $forms_url . $filename,
                'filename' => $filename,
                'title' => $title,
                'size' => size_format(filesize($filepath), 1)
            );
        }
    }
    
    return $forms;
}

// =============================================================================
// GITHUB AUTO-UPDATER
// =============================================================================

class D219_GitHub_Updater {
    private $slug, $plugin_data, $github_response;
    public function __construct($f) {
        $this->slug = plugin_basename($f);
        add_filter('pre_set_site_transient_update_plugins', array($this, 'check_update'));
        add_filter('plugins_api', array($this, 'plugin_info'), 20, 3);
    }
    private function get_plugin_data() { if (empty($this->plugin_data)) $this->plugin_data = get_plugin_data(D219_TRANSITION_PLUGIN_FILE); return $this->plugin_data; }
    private function get_github_release() {
        if (!empty($this->github_response)) return $this->github_response;
        $r = wp_remote_get('https://api.github.com/repos/cameronsuorsa/d219-transition-page/releases/latest', array('headers' => array('Accept' => 'application/vnd.github.v3+json', 'User-Agent' => 'WordPress/' . get_bloginfo('version'))));
        if (is_wp_error($r) || wp_remote_retrieve_response_code($r) !== 200) return false;
        $this->github_response = json_decode(wp_remote_retrieve_body($r));
        return $this->github_response;
    }
    public function check_update($t) {
        if (empty($t->checked)) return $t;
        $rel = $this->get_github_release(); if (!$rel) return $t;
        $pd = $this->get_plugin_data(); $gv = ltrim($rel->tag_name, 'v');
        if (version_compare($gv, $pd['Version'], '>')) {
            $dl = $rel->zipball_url;
            if (!empty($rel->assets)) { foreach ($rel->assets as $a) { if (strpos($a->name, '.zip') !== false) { $dl = $a->browser_download_url; break; } } }
            $t->response[$this->slug] = (object) array('slug' => dirname($this->slug), 'new_version' => $gv, 'url' => $rel->html_url, 'package' => $dl);
            // Send email notification once per version
            $this->maybe_send_update_email($gv, $rel);
        }
        return $t;
    }
    private function maybe_send_update_email($new_version, $rel) {
        $notified_key = 'd219_update_notified_' . $new_version;
        if (get_option($notified_key)) return; // Already notified for this version

        $admin_email = get_option('admin_email');
        $site_name = get_bloginfo('name');
        $site_url = home_url();
        $current_version = $this->get_plugin_data()['Version'];

        // Build one-click update URL (requires admin login)
        $update_url = admin_url('update.php?action=upgrade-plugin&plugin=' . urlencode($this->slug));

        // Parse changelog from release body
        $changelog = $rel->body;
        // Convert markdown bold **text** to plain text for email
        $changelog_plain = preg_replace('/\*\*([^*]+)\*\*/', '$1', $changelog);
        // Convert markdown links [text](url) to text (url)
        $changelog_plain = preg_replace('/\[([^\]]+)\]\(([^)]+)\)/', '$1 ($2)', $changelog_plain);

        $subject = "[{$site_name}] District 219 Transition Plugin Update Available: v{$new_version}";

        $message = "A new version of the District 219 Transition Page plugin is available for {$site_name}.\n\n";
        $message .= "Current version: {$current_version}\n";
        $message .= "New version: {$new_version}\n\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        $message .= "WHAT'S NEW\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        $message .= $changelog_plain . "\n\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        $message .= "HOW TO UPDATE\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        $message .= "Option 1 — One-click update (requires admin login):\n";
        $message .= $update_url . "\n\n";
        $message .= "Option 2 — WordPress Dashboard:\n";
        $message .= admin_url('plugins.php') . "\n";
        $message .= "Look for the update notice under \"District 219 Transition Page\"\n\n";
        $message .= "Option 3 — GitHub release page:\n";
        $message .= $rel->html_url . "\n\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        $message .= "This is an automated notification from the D219 Transition plugin on {$site_url}\n";

        wp_mail($admin_email, $subject, $message);
        update_option($notified_key, true);
    }
    public function plugin_info($r, $a, $args) {
        if ($a !== 'plugin_information' || dirname($this->slug) !== $args->slug) return $r;
        $rel = $this->get_github_release(); if (!$rel) return $r;
        $pd = $this->get_plugin_data();
        return (object) array('name' => $pd['Name'], 'slug' => dirname($this->slug), 'version' => ltrim($rel->tag_name, 'v'), 'author' => $pd['Author'], 'homepage' => $pd['PluginURI'], 'sections' => array('description' => $pd['Description'], 'changelog' => nl2br($rel->body)), 'download_link' => $rel->zipball_url);
    }
    // Clean up notification flags when update completes
    public function after_update() {
        global $wpdb;
        $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE 'd219_update_notified_%'");
    }
}
if (is_admin()) new D219_GitHub_Updater(D219_TRANSITION_PLUGIN_FILE);

// =============================================================================
// REWRITE RULES
// =============================================================================

add_action('init', function() {
    add_rewrite_rule('^transition/?$', 'index.php?d219_transition=1', 'top');
    add_rewrite_rule('^dlc/?$', 'index.php?d219_dlc=1', 'top');
    add_rewrite_tag('%d219_transition%', '([^&]+)');
    add_rewrite_tag('%d219_dlc%', '([^&]+)');
    
    // Auto-flush rewrite rules when plugin version changes (handles updates)
    $stored_version = get_option('d219_transition_version');
    if ($stored_version !== D219_TRANSITION_VERSION) {
        flush_rewrite_rules();
        update_option('d219_transition_version', D219_TRANSITION_VERSION);
    }
});
register_activation_hook(__FILE__, function() {
    add_rewrite_rule('^transition/?$', 'index.php?d219_transition=1', 'top');
    add_rewrite_rule('^dlc/?$', 'index.php?d219_dlc=1', 'top');
    add_rewrite_tag('%d219_transition%', '([^&]+)');
    add_rewrite_tag('%d219_dlc%', '([^&]+)');
    flush_rewrite_rules();
    update_option('d219_transition_version', D219_TRANSITION_VERSION);
});
register_deactivation_hook(__FILE__, function() { 
    flush_rewrite_rules(); 
    delete_option('d219_transition_version');
});

// Use template_include filter instead of template_redirect for better compatibility
add_filter('template_include', function($template) {
    if (get_query_var('d219_transition')) {
        $custom_template = D219_TRANSITION_PLUGIN_DIR . 'template-transition.php';
        if (file_exists($custom_template)) {
            return $custom_template;
        }
    }
    
    if (get_query_var('d219_dlc')) {
        $custom_template = D219_TRANSITION_PLUGIN_DIR . 'template-dlc.php';
        if (file_exists($custom_template)) {
            return $custom_template;
        }
    }
    
    return $template;
});

// =============================================================================
// ENQUEUE STYLES
// =============================================================================

// Enqueue external styles (Google Fonts, Font Awesome) — only on plugin pages
add_action('wp_enqueue_scripts', function() {
    if (get_query_var('d219_transition') || get_query_var('d219_dlc')) {
        // Google Fonts
        wp_enqueue_style('d219-google-fonts', 'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Source+Sans+Pro:wght@400;600&display=swap', array(), null);

        // Only load Font Awesome if not already enqueued by theme/Elementor
        $fa_loaded = wp_style_is('font-awesome', 'enqueued')
            || wp_style_is('font-awesome-5-all', 'enqueued')
            || wp_style_is('elementor-icons-fa-solid', 'enqueued')
            || wp_style_is('fontawesome', 'enqueued');
        if (!$fa_loaded) {
            wp_enqueue_style('d219-font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css', array(), '6.5.1');
        }
    }
}, 999);

// Inject plugin CSS directly to bypass SiteGround Optimizer CSS combination
// (SG Optimizer drops/fails to combine local plugin CSS with spaces in folder path)
add_action('wp_head', function() {
    $is_plugin_page = get_query_var('d219_transition') || get_query_var('d219_dlc');

    if ($is_plugin_page) {
        // Full CSS on /transition and /dlc pages
        $css_file = D219_ASSETS_DIR . 'transition-styles.css';
        if (file_exists($css_file)) {
            echo "\n<style id=\"d219-transition-styles\">\n";
            readfile($css_file);
            echo "\n</style>\n";
        }
    } elseif (D219_SHOW_BANNER) {
        // Minimal banner-only CSS on all other pages
        ?>
        <style id="d219-banner-styles">
        .d219-transition-banner{background:#004165!important;color:#fff!important;text-align:center!important;padding:12px 16px!important;font-size:14px!important;font-family:'Source Sans Pro',Arial,sans-serif!important;display:block!important;width:100%!important}
        .d219-transition-banner .d219-banner-219{color:#F2DF74!important;font-weight:700!important}
        .d219-transition-banner a{color:rgba(255,255,255,0.7)!important;font-weight:600!important;text-decoration:underline!important;margin-left:8px!important}
        .d219-transition-banner a:hover{color:#F2DF74!important}
        </style>
        <?php
    }
}, 999);

// Add body class on transition/dlc page to hide footer
add_filter('body_class', function($classes) {
    if (get_query_var('d219_transition') || get_query_var('d219_dlc')) {
        $classes[] = 'd219-hide-footer';
    }
    return $classes;
});

// =============================================================================
// BANNER
// =============================================================================

add_action('wp_body_open', function() {
    if (D219_SHOW_BANNER) {
        ?>
        <div class="d219-transition-banner">
            <a href="/transition">Transition</a>: D10 &amp; D13 merge to become <span class="d219-banner-219">D219</span> on July 1st. <a href="/dlc">DLC Nominations</a> close Feb 25th.
        </div>
        <?php
    }
});

// =============================================================================
// REDIRECT #nominations ANCHOR TO /dlc
// =============================================================================

add_action('wp_footer', function() {
    if (get_query_var('d219_transition')) {
        ?>
        <script>
        (function() {
            if (window.location.hash === '#nominations') {
                window.location.replace('/dlc');
            }
        })();
        </script>
        <?php
    }
});
