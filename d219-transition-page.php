<?php
/**
 * Plugin Name: District 219 Transition Page
 * Plugin URI: https://github.com/cameronsuorsa/d219-transition-page
 * Description: Creates a /transition page for District 219 Toastmasters transition information.
 * Version: 2.2.0
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
define('D219_ZOOM_LINK', 'https://www.eventbrite.com/e/toastmasters-district-219-annual-district-council-business-meeting-tickets-1985778401655?aff=oddtdtcreator'); // Eventbrite registration
define('D219_DLC_MODE', 'candidates'); // 'candidates' = show nominated slate, 'nominations' = show call for nominations

// Publish date/time (Eastern). When set and in the past:
//   - /staging/transition and /staging/dlc will 301-redirect to /transition and /dlc
//   - /transition and /dlc will serve content (D219_DLC_MODE will be used as-is)
// When empty string: staging URLs work, live URLs use D219_DLC_MODE as normal.
// Format: 'YYYY-MM-DD HH:MM' in America/New_York timezone, e.g. '2026-04-01 09:00'
define('D219_PUBLISH_DATE', '2026-03-20 00:00'); // Midnight ET, March 20

// =============================================================================
// PRE-PRODUCTION CHECKLIST — Items needed before D10 can update the live plugin
// =============================================================================
// [x] Bio PDF for Jolyn Redic — added from unlocked PDF
// [x] DLC Nomination Report — styled as committee report card from Melissa on /dlc page
// [ ] Candidate Showcase Videos (available after April 22, 2026)
// [ ] Verify Quick Interest Form — remove elected position options from form
// [ ] Melissa / transition committee page review & feedback
// [x] Set D219_DLC_MODE to 'candidates'
// [x] Set D219_PUBLISH_DATE to coordinated release date/time with both DDs
// [ ] Coordinate release timing with D10 (Tricia) and newsletters from both districts
// [ ] Remove "NOT READY FOR D10 UPDATE" from release notes
// =============================================================================
// PLUGIN CONSTANTS
// =============================================================================

define('D219_TRANSITION_VERSION', '2.2.0');
define('D219_TRANSITION_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('D219_TRANSITION_PLUGIN_URL', plugin_dir_url(__FILE__));
define('D219_TRANSITION_PLUGIN_FILE', __FILE__);

define('D219_ASSETS_URL', D219_TRANSITION_PLUGIN_URL . 'assets/');
define('D219_ASSETS_DIR', D219_TRANSITION_PLUGIN_DIR . 'assets/');

// =============================================================================
// PUBLISH HELPERS
// =============================================================================

/**
 * Check if the publish date has passed (content should be live at normal slugs).
 * Returns true if D219_PUBLISH_DATE is set and in the past (Eastern time).
 */
function d219_is_published() {
    return true; // Content is live — publish date has passed (2026-03-20)
}

/**
 * Get the correct URL prefix for cross-links between plugin pages.
 * When on staging, links point to /staging/slug. When live, links point to /slug.
 */
function d219_page_url($slug) {
    $is_staging = get_query_var('d219_staging');
    if ($is_staging && !d219_is_published()) {
        return '/staging/' . ltrim($slug, '/');
    }
    return '/' . ltrim($slug, '/');
}

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
// DATE STATUS HELPER
// =============================================================================

/**
 * Returns 'past', 'current', or 'future' for a given date string.
 * 'current' = the next upcoming event (first future date found).
 */
function d219_date_status($date_str) {
    $event_date = strtotime($date_str);
    $today = strtotime('today');
    return ($event_date < $today) ? 'past' : 'future';
}

// =============================================================================
// CANDIDATE DATA (2026-2027 Nominated Slate)
// =============================================================================

function d219_get_candidates() {
    return array(
        array(
            'role' => 'District Director',
            'type' => 'elected',
            'candidates' => array(
                array('name' => 'Sharon Imes, DTM', 'photo' => 'sharon-imes.webp', 'bio' => 'sharon-imes.pdf'),
            )
        ),
        array(
            'role' => 'Program Quality Director',
            'type' => 'elected',
            'candidates' => array(
                array('name' => 'Lorie Davis, DTM', 'photo' => 'lorie-davis.webp', 'bio' => 'lorie-davis.pdf'),
                // WITHDRAWN: array('name' => 'Javier Diaz, LD5, PM1', 'photo' => 'javier-diaz.webp', 'bio' => 'javier-diaz.pdf'),
                array('name' => 'Stephanie Hill, IP5, LD5, PI3, MS3, EH1', 'photo' => 'stephanie-hill.webp', 'bio' => 'stephanie-hill.pdf'),
            )
        ),
        array(
            'role' => 'Club Growth Director',
            'type' => 'elected',
            'candidates' => array(
                array('name' => 'Ed Haller, ACG, ALB, PM5, DL2', 'photo' => 'ed-haller.webp', 'bio' => 'ed-haller.pdf'),
                // WITHDRAWN: array('name' => 'Tamika Leslie, DL5, VC5, EH1', 'photo' => 'tamika-leslie.webp', 'bio' => 'tamika-leslie.pdf'),
            )
        ),
        array(
            'role' => 'Division A Director',
            'type' => 'division',
            'region' => 'Western & Central OH',
            'candidates' => array(
                array('name' => 'Autumn Jose, PM5, DL3', 'photo' => 'autumn-jose.webp', 'bio' => 'autumn-jose.pdf'),
                // WITHDRAWN: array('name' => 'Jolyn Redic, DTM', 'photo' => 'jolyn-redic.webp', 'bio' => 'jolyn-redic.pdf'),
            )
        ),
        array(
            'role' => 'Division B Director',
            'type' => 'division',
            'region' => 'Eastern OH & Erie, PA',
            'candidates' => array(
                array('name' => 'Adam Brown, PI5', 'photo' => 'adam-brown.webp', 'bio' => 'adam-brown.pdf'),
            )
        ),
        array(
            'role' => 'Division C Director',
            'type' => 'division',
            'region' => 'Akron, Canton & Southern OH',
            'candidates' => array(
                array('name' => 'Megan Rossetti, PM4', 'photo' => 'megan-rossetti.webp', 'bio' => 'megan-rossetti.pdf'),
            )
        ),
        array(
            'role' => 'Division D Director',
            'type' => 'division',
            'region' => 'North & West Pittsburgh, PA',
            'candidates' => array(
                array('name' => 'James Leslie, PI5', 'photo' => 'james-leslie.webp', 'bio' => 'james-leslie.pdf'),
            )
        ),
        array(
            'role' => 'Division E Director',
            'type' => 'division',
            'region' => 'Central & South Pittsburgh, PA',
            'candidates' => array(
                array('name' => 'Stuart Strickland, DTM', 'photo' => 'stuart-strickland.webp', 'bio' => 'stuart-strickland.pdf'),
            )
        ),
        array(
            'role' => 'Division F Director',
            'type' => 'division',
            'region' => 'Central PA, WV & MD',
            'candidates' => array(
                array('name' => 'Catherine Cullen, VC5, MS4, PM3, DL1', 'photo' => 'catherine-cullen.webp', 'bio' => 'catherine-cullen.pdf'),
                // WITHDRAWN FROM DIV F (remains on Div A): array('name' => 'Autumn Jose, PM5, DL3', 'photo' => 'autumn-jose.webp', 'bio' => 'autumn-jose.pdf'),
                // WITHDRAWN: array('name' => 'Cosmas Nwakanma, IP5, MS2, DL2', 'photo' => 'cosmas-nwakanma.webp', 'bio' => 'cosmas-nwakanma.pdf'),
            )
        ),
    );
}

// =============================================================================
// CANDIDATE BIO DATA (extracted from 450H PDF forms)
// =============================================================================

function d219_get_candidate_bios() {
    $candidates = d219_get_candidates();
    $flat = array();
    foreach ($candidates as $role) {
        foreach ($role['candidates'] as $c) {
            $slug = sanitize_title(explode(',', $c['name'])[0]);
            if (isset($flat[$slug])) {
                // Dual-nomination handling (kept for flexibility; currently no candidates hold two roles)
                $flat[$slug]['role'] = $flat[$slug]['role'] . ' & ' . $role['role'];
                $flat[$slug]['region'] = $flat[$slug]['region'] . ' / ' . (isset($role['region']) ? $role['region'] : '');
                continue;
            }
            $flat[$slug] = array(
                'slug' => $slug,
                'name' => explode(',', $c['name'])[0],
                'credentials' => trim(substr($c['name'], strpos($c['name'], ',') + 1)),
                'role' => $role['role'],
                'type' => $role['type'],
                'region' => isset($role['region']) ? $role['region'] : '',
                'photo' => $c['photo'],
                'bio_pdf' => $c['bio'],
                'video' => null,
                'answers' => array(),
            );
        }
    }

    // Sharon Imes — District Director
    $flat['sharon-imes']['answers'] = array(
        'member_since' => '1993',
        'education' => 'Harvard Business School: Leading Change and Organizational Renewal - Current. NYU: AA: Business Administration, BA Management & Leadership. Dale Carnegie: Human Relations and Effective Speaking Certificate. Train the Trainer: Certificate. AMTC: Acting Modeling: Certificate. VOP, NY Radio: Broadcaster.',
        'offices' => 'Program Quality Director: 2025-2026. Club Sponsor and President: Western Maryland 2024-2025. President: Laurel Highlands: 2024-2025. VP-PR: Laurel Highlands: 2023-2024. Club Sponsor and President Laurel Highlands 2021-2022. Area Governor and Division Governor - District 46-1996-1998.',
        'honors' => '2 Congratulatory Letters from TI for leading two clubs to President\'s Distinguished 2025. Appreciation Plaque from District 13 District Director, for helping District 13 become a Smedley Distinguished District in 2024 by starting a new club in Western Maryland. Started two community clubs and had the Charter / Induction Ceremony as two Red Carpet events. One club has a State Senator as a Charter Member in PA, and a State Senator as a Keynote Speaker in Maryland, and a Governor\'s and a Senator\'s Citation for being the only club in Western Maryland. Won District 13 International Speech Contest 2022. Negotiated a $10K sponsorship for the District 46 TM Conference in NY, which I chaired. First Distinguished Toastmaster Award, June 2005. Second Distinguished Toastmaster expected June 2026. Distinguished Division Director.',
        'work_experience' => 'Business Consultant and CEO, Breakthrough Leadership Institute international (BLII) - Training, development and business consulting. Business Growth Consultant: for over 250 businesses in Connellsville, PA. Sat on the Downtown Connellsville Council with the state Senator and other business leaders to ignite business growth. This included producing Downtown events for businesses and the community. Lessons learned: The ability to collaborate with politicians, business leaders and the community, including the mayor\'s office to accomplish a set outcome. Consulted with international companies on business development and marketing including an $11 billion Merger and Acquisition finalized in Canada. This included leasing a plane to fly in executives to the final signing. Lesson learned: How to work with Businesses, airlines, hotels, executives and laymen of different cultures, languages and values internationally and locally to find solutions and accomplish a specific objective. Managed the Operations of Standard and Poor\'s: Structured Finance Department (the S&P 500). Including over 500 employees, with 55 direct reports, with diverse backgrounds, educational level, cultures and financial status; this included onboarding and offboarding interviews: Lesson learned: Executive Management, Collaboration: how to listen and find value in the opinion of others. How to create training programs for management and staff including Business Etiquette and Dress for Success training for the S&P University. How to navigate a male dominated environment, manage budgets and complete Progress Reports for Senior Management, and prepared Presentations for BOD.',
        'strategic_planning' => 'As a member of the Board of Directors of the Allegany Chamber of Commerce, we\'re currently in the process of creating a strategic plan for the Chamber\'s future. As a Business Consultant, I help many businesses develop a strategic plan for the future of the business, and for marketing growth and success. As President of two TM clubs in two states, last year, Western Maryland and Laurel Highlands in PA. I created a strategic plan for growth and educational achievements with the support of Officers that allowed both Clubs to become President\'s Distinguished. Western Maryland TM Club was a newly Chartered club with only 3 previous Toastmaster members, and my strategic plan allowed that club to become President\'s Distinguished in the first year. The Club in PA was in need of a Club Coach at 7 or 8 members when I became President, and we ended the year becoming President\'s Distinguished with the highest educational goals per membership in District 13 and no longer in need of a Club Coach.',
        'finance' => 'Currently manage the budget for administration and staff for our construction company, and for my consulting business and the budgets of three different households including 2 that requires medical equipment, and staffing. At S&P I managed the Structured Finances budget with a team.',
        'procedures' => 'I create procedures on how our current staff operate, and sometimes tenants in special situations. As a Member of the Board of Directors of the Chamber, we develop procedures for the effective running of the Chamber. At Standard & Poor\'s, I created policies and procedures for my Direct Reports and for the Structures Finance group in collaboration with other department leaders.',
        'leadership_lessons' => 'The importance of Integrity, Trust and Respect for all team members. The amazing value of team collaboration and the art of positioning team members with purpose to successful accomplish tasks and objectives. How to deal with executives and people who work in the mailroom. The value of communicating and listen to ideas and insights from others to understand their needs and what\'s important to them, and how to break down big goals into simple steps to achieve a greater outcome.',
        'why_serve' => 'I believe that my national and international leadership, training and development skills and my ability to collaborate and reach a consensus with diverse groups of people from different cultures, will allow me to effectively integrate our people and clubs into a unified District. I want to help our new District achieve their Educational and Club growth goals. Toastmasters have been my secret weapon throughout my career as a leader on Wall Street, as a Business Consultant to hundreds of businesses in different states and countries, and in my current positions as the Marketing Director for our Construction company and CEO of BLII, I want to share the secret of my success in leading clubs and members to success and how I overcame challenges personally and professionally using Toastmasters, with District 219. I had a major speech impediment from childhood into adulthood. I felt like an outcast with no friends, but because of Toastmasters\' positive and supportive learning, growing environment; I can now communicate effectively, and I\'m still learning and growing in this supportive organization. I believe I can give Clubs and member leaders the support, encouragement and inclusion that Toastmasters has given me, to help them achieve their personal communication and leadership goals. How? By deliberately caring about all member clubs, supporting them to grow and showing them how to leverage the invaluable benefits of their Toastmaster membership on the job and in their communities. I want to collaborate with current and former leaders of both districts to bring out the best in all clubs, build new ones and create strategies to effectively reach out to our communities with the benefits of Toastmasters. Finally, we all have values, visions and voids. I want to work with all our district leaders and Clubs to develop training programs to help members fill their void and achieve their Toastmaster vision to live with purpose. I want to share the joy of life that I carry to help people shine.',
        'district_objectives' => 'The District mission\'s major objectives are to support all clubs to help members build communication and leadership skills. Skills that should impact their personal and professional lives in a way that supports the club\'s mission and exemplifies or fulfill International\'s tagline of: \'Toastmasters: where leaders are made\'. The District also assist in building new clubs within our boundary. To achieve this, I would develop more creative ways of supporting clubs, such District Led \'Lunch and Learns\' on different topics that supports TI mission and impact members personally and professionally. For example, trainings on how to leverage your TM skills to get a promotion, start a business and improve their family and community or how to leverage your Toastmasters Network to accomplish your life\'s purpose. etc. I also believe that there are many untapped communities and businesses that can benefit from Toastmasters - Brand names are important - we can leverage the corporate brand names that value Toastmasters to get more corporate clubs and by participating in community events and doing Speechcraft to get more community clubs and members, and conduct Youth Leadership programs to prepare a pipeline of new Toastmasters.',
        'additional_info' => 'I love life and I love people - they are our greatest asset. I believe that leadership is about influencing people to accomplish a purpose. It\'s about turning followers into leaders and preparing and allowing those leaders to accomplish established goals, while using their gifts and abilities to accomplish their life\'s purpose and impact their communities.',
    );

    // Lorie Davis — Program Quality Director
    $flat['lorie-davis']['answers'] = array(
        'member_since' => '2021',
        'education' => 'BA Slippery Rock University (French, Education, Spanish). Certified in HealthCare Compliance (CHC). Six Sigma Belts (White, Yellow, Green).',
        'offices' => 'Secretary - Treasurer - 2021-2022. VP Membership - 2022-2023. President / VP Education - 2023-2024. Vice-President Education 2025-2026.',
        'honors' => 'PM 5, EC5, DTM pending. Area 34 Director. Division D Director.',
        'work_experience' => 'I work in Healthcare Compliance and am used to working across the organization with folks for External Audit, Vendor Oversight, Risk Management, and Policy Book coordination.',
        'strategic_planning' => 'As part of Compliance, we are required to create a strategic Compliance, Audit, and Board plan annually and review progress. In addition, I manage a policy book and am in charge of coordinating across the organization the review, update, and approval of the associated Policies and Procedures.',
        'finance' => 'I have not worked directly in Finance; however, I have supported Finance for External Audits and other related fiduciary responsibilities.',
        'procedures' => 'I have managed several policy books and current have a repository of over 360 policies. As I work in Compliance, writing policies is a major focus of what I do. I have written numerous policies and been responsible for the review, update, and maintenance of policies.',
        'leadership_lessons' => 'Leadership is best when you collaborate and key the lines of communication open. It\'s essential to have recurring, consistent channels of communication. Toxicity can and will run down fellow leaders and it\'s essential to address it straight away. Staying focused on your mission and involving the rest of the DEC is a huge path to success.',
        'why_serve' => 'I\'d like to serve as a District Leader to continue to give back to the organization and the many folks who have supported me over the years. I also want to be part of the merged District as a way to celebrate the best of what Toastmasters is and can be.',
        'district_objectives' => 'As we\'re a newly merged District, stability and reassuring the membership that it\'s business as usual is an essential element of the role as a Trio member. In my role as Program Quality Director, I\'d work with the District Executive Committee across the District to ensure that we keep things as "Business as Usual" whilst infusing the best parts of both Districts into our 2026-2027 mission.',
        'additional_info' => 'I am grateful for the opportunity to apply for this role and be in the position to serve the District.',
    );

    // WITHDRAWN — Javier Diaz — Program Quality Director
    // Data preserved but removed from frontend per Melissa's request
    /*
    $flat['javier-diaz']['answers'] = array(
        'member_since' => '2020',
        'education' => 'Attended University of Puerto Rico, Risk Management, BA. 1996. Scrum Master Certified, Scrum Master Product Owner Certified, PMI Member.',
        'offices' => 'Club Growth Director 2025-2026; Eastern Division Director 2024-2025; Area Director Area 10 and 21, 2023-2024; President of Diversity for Success Club 2023-2024. VP of Education Progressive Messengers 2023-2024; VP of Public Relations, Progressive Messengers 2022-2023; Seargeant at Arms, Progressive Messengers 2021-2022.',
        'honors' => 'Speech Contest First Place, Progressive Messengers December of 2022. Triple Crown and LD5, PM1. Area 21 Select Distinguished, 2023-2024, Division Director of the Year 2024-2025.',
        'work_experience' => 'Over 20 years of experience working with Fortune 500 and 100 companies in the area of Information Technology quality assurance and analysis in leading roles which includes Insurance, Banking, and Healthcare. I have spent around 12 years at Progressive supporting and leading the quality assurance of major projects that span multiple organizations and emerging technologies. Most recently, innovating with AI, ML, and Natural Language Telecommunications.',
        'strategic_planning' => 'Most recently, as a CGD for District 10 I have developed a multiyear plan to penetrate the local market while leveraging latest technology and footprint changes. Part of my day job duties include the strategic analysis, planning, testing and quality assurance activities for major projects that require rigorous test strategies and approaches to secure the highest level of quality for critical projects that involve artificial intelligence, customer experience, innovative technologies. This includes handling relationships with outside vendors and assisting in decision making for major designs.',
        'finance' => 'As a current CGD, I have been directly involved in budgeting and planning for district expenditures. In my early years, I managed accounts payables and accounts receivables, including balance sheets and statements for a family insurance agency and other early career experiences. Also, while working as a quality assurance consultant at National City and PNC, I managed the testing and quality of Finance systems that decisioned major loans for multi-million dollars corporations across United States, including the quality assurance of financial systems, credit decisioning tools, etc.',
        'procedures' => 'I have developed quality assurance, quality control, and automation procedures in various companies I have worked as a consultant. I have also worked in designing training, designing events, and management of multiple projects as a Board Member of the Latino Employee Resource Group, PLANETA in which I have served as a Board Member for almost 5 years. As CGD, I have created a corporate and community strategy and procedure to study, reach out, communicate, and tailor our message from lead to market to charter approach.',
        'leadership_lessons' => 'Clear communication, empathy and proactive planning are key to establish and maintain successful working relationships. Active listening and situational leadership along with teamwork are essential in the success recipe. Highly engaged individuals are discovered when given the platforms and tools to excel at what they love to do. I have also learned that Toastmasters members and leaders are willing to join your missions and projects. They are willing to answer the call whenever you ask for help. Extend the table and they will join you.',
        'why_serve' => 'I am a quality over quantity advocate. I believe in first impressions. As an advocate for young Latino leaders, young professionals, and legacy knowledge bridging, my focus is on encouraging education, creating and serving Toastmasters clubs in underrepresented communities. With the clubs that are currently in the pipeline and the ones positioned to charter, I want to inspire them to continue the journey. My desire is to revamp the Toastmasters experience with pristine quality where learning is a sough after activity rather than a checkbox. My goal is to rekindle the desire of service, teaching, and advocacy for Toastmasters community that would revive Dr. Smedley\'s dream.',
        'district_objectives' => 'As a new district, the mission is to safeguard the realignment, secure ROI, revive a new spirit of unity and work harmoniously while adhering to our global mission of building new clubs and supporting all clubs achieve excellence. Promote Toastmasters, create new clubs, increase retention, and help clubs thrive. We need to nurture our members with regular cadence of training, prepare them for future opportunities and attract local employers and schools to invest in Toastmasters. Collaborate with local corporations in Open Houses to raise awareness and attract leaders to mentor new members.',
        'additional_info' => 'Received Division Director of the Year Award, 2024-2025. Received Progressive FLOIE award in two consecutive years for the most collaborative in 2022 and the most creative in 2023. Through my ERG board contributions, I have led major projects, events, and have mentored successful delegates and senior leaders. I continue to receive above-and-beyond year reviews and trusted with highly visible projects. I am a Quality Assurance Leader leading major projects in Telecommunications, Artificial Intelligence and multilingual efforts. I also speak fluent Spanish and serve as a mentor and counselor in youth communities. Also, compose songs and play piano.',
    );
    */

    // Stephanie Hill — Program Quality Director
    $flat['stephanie-hill']['answers'] = array(
        'member_since' => '2019',
        'education' => 'Master of Labor Relations and Human Resources, Cleveland State University 2008-2011. Bachelor of Business Administration concentration in Information Systems, Cleveland State University 2005-2008. Associated Degree Liberal Arts, 2002-2005.',
        'offices' => 'President for both Progressive Messengers (2022-2023) and Progressive Advanced Club (2023-2024), Vice President of Education Progressive Messengers (2021-2022) and Diversity 4 Success (2024-2026), VP of Membership Diversity 4 Success (2024-2025). Treasurer Progressive Messengers (2020-2022) and Progressive Advanced Club (2023-2024), Central Area Director 2023-2024, Central Division Director (2024-2025 and Eastern Division Director (2025-2026), Administrative Manager (2025-2026).',
        'honors' => 'Active Toastmasters member with sustained leadership service. Selected for District-level leadership roles. Recognized for mentoring members and supporting officer training initiatives.',
        'work_experience' => 'I bring over 20 years of professional experience in information technology, systems administration, project management, healthcare operations, people leadership, and business ownership. Throughout my career, I have led cross-functional teams, supported enterprise systems, developed training programs, mentored employees, and partnered with stakeholders to achieve organizational goals. This experience directly supports my work as a District leader by enabling me to manage complex initiatives, communicate effectively with diverse audiences, mentor club and district leaders, and implement sustainable processes that strengthen clubs and member engagement.',
        'strategic_planning' => 'I have extensive experience in strategic planning through leading technical projects, managing large-scale system initiatives, coordinating cross-functional teams, and aligning operational goals with organizational objectives. As a Toastmasters leader, I apply strategic planning to club support, officer development, training initiatives, and long-term district success by setting clear priorities, measuring progress, and adjusting plans as needed.',
        'finance' => 'My experience includes budget and timeline management, vendor coordination, contract oversight, and financial decision-making in both corporate and entrepreneurial environments. As a business owner, I manage budgets, negotiate contracts, and oversee financial accountability. As a Toastmasters leader and former club Treasurer, I apply fiscal responsibility, transparency, and planning to ensure financial health and sustainability.',
        'procedures' => 'I have significant experience developing procedures through process improvement initiatives, system documentation, training program creation, and standard operating procedures in corporate, healthcare, and small-business environments. In Toastmasters, I help establish clear processes for officer transitions, training delivery, meeting operations, and district initiatives to promote consistency, clarity, and success.',
        'leadership_lessons' => 'I have learned that effective leadership requires clear communication, active listening, adaptability, and empowering others. Strong leaders meet people where they are, provide guidance and support, and create an environment where individuals feel valued and confident to grow. I also learned that sustainable success comes from collaboration, accountability, and leading by example.',
        'why_serve' => 'I am passionate about helping others find their voice, develop confidence, and grow as leaders. Serving as a District leader allows me to combine my professional expertise with my love for Toastmasters by mentoring leaders, strengthening clubs, supporting member success, and contributing to the long-term health and growth of the District.',
        'district_objectives' => 'The District mission\'s major objectives are to support clubs in achieving excellence, develop effective leaders, and provide a positive member experience. I would work to achieve these objectives by strengthening officer training, fostering collaboration across clubs, encouraging mentorship, promoting clear communication, and supporting strategic initiatives that enhance member engagement, retention, and growth.',
        'additional_info' => 'In addition to my professional and Toastmasters leadership experience, I am a business owner and mentor who values continuous learning, inclusivity, and service. I am a graduate of the Multicultural Leadership Development Program (MLDP) and am committed to building strong relationships across diverse backgrounds while inspiring others to lead with confidence and purpose.',
    );

    // Ed Haller — Club Growth Director
    $flat['ed-haller']['answers'] = array(
        'member_since' => '2013',
        'education' => 'B.S. Chemical Engineering (University of Akron). MBA (University of Phoenix) 4.0 GPA.',
        'offices' => 'D10 Club Extension Chair \'17-\'18 & \'25-\'26; D10 Area Director \'16-\'17 & \'25-\'26. Club President \'17-\'18; \'19-\'20; \'25-\'26; Vice President Education \'15-\'16; \'18-\'19. Vice President Membership \'14-\'15; Club Secretary \'20-\'21.',
        'honors' => '2015 District 10 Humorous Speech Contest Winner: Falling Bridges. 2014 District 10 Humorous Speech Contest Winner: Short Stories.',
        'work_experience' => 'Director of Water Pollution Control, City of Warren, Ohio 12/14 through 3/24. Department Head, 44 Employees; Leader directly responsible for all aspects of my department. Wastewater Superintendent, City of North Royalton, Ohio 4/24 to present. Department Head, 31 Employees; Leader directly responsible for all aspects of my department.',
        'strategic_planning' => 'District 10 Strategic Planning Committee Co-Chair \'25-\'26. Director of Water Pollution Control, City of Warren, Ohio 12/14 through 3/24; Strategic Plan (\'15-\'16) resulting in a Capital Improvement Plan of $120M; Stormwater Master Plan (\'23-\'24). Wastewater Superintendent, City of North Royalton, Ohio 4/24 to present; Strategic Planning in Progress (\'25-\'26).',
        'finance' => 'Director of Water Pollution Control, City of Warren, Ohio 12/14 through 3/24; $13M Annual Operating Budget. Completed a Sewer Rate Study and gained City Council approval for increased sewer rates 42% over 6 years; Oversaw multiple construction projects: Plant Renovations $28M, Sewer Pump Station Renovations $19M. Wastewater Superintendent, City of North Royalton, Ohio 4/24 to present; $13M Annual Operating Budget.',
        'procedures' => 'Developed plant equipment operating procedures, Lab Analysis procedures, EPA Reporting procedures, Safety procedures including Lock-Out-Tag-Out & Evacuation. Developed a 17-week Course on Wastewater Theory & Math Calculations: Taught it 15 times. 12 of my employees earned 25 EPA licenses in 9 years at Warren. Authored both Simplified Wastewater Treatment Plant Operations (Wastewater Math & Theory) Text & Workbook (Routledge).',
        'leadership_lessons' => 'We are a volunteer organization and must earn respect through giving respect. Leadership is always, and especially in a volunteer organization, Influence, not authority. Everybody is busy, so schedules need to be especially respected. Everyone is in Toastmasters for a reason, many to improve professional skills. If we can align our requests for assistance with augmentation of those member skills, everyone wins. We benefit by looking at our challenges from a fresh perspective to find better solutions.',
        'why_serve' => 'My planning and organizational skills previously placed me in leadership positions. However, I was afraid of public speaking, especially impromptu. Through Toastmasters Educational Programs and especially the Speech Contests, I significantly improved my public speaking skills. I want to offer my developed leadership and public speaking skills to give back to the District and ensure that others have the same professional development opportunities that tremendously benefitted me in my wastewater career.',
        'district_objectives' => 'We are supposed to build new clubs and support all clubs in achieving excellence. However, over many years we have consistently lost more members and clubs than we have added. I have a plan to start with fortifying our struggling clubs. I then intend to drive an effective District marketing plan to attract new members to all District clubs. Finally, I hope to inspire every District Area and Division to be involved with me and my team in starting a group of new community and corporate clubs.',
        'additional_info' => 'This is a transitional year as we merge D10 and D13 into D219. I love working with most people. As a veteran of D10, I intentionally spent time, attending many events in person and online with D13 this past year and really enjoyed getting to know many new friends from the other side of our expanded District family. Over my years in wastewater leadership, I have developed and used effective Planning and Change Management skills. The D219 Leadership will need to be sensitive to the concerns of merging the cultures of two Districts. I offer my skills to smooth out those transitions.',
    );

    // WITHDRAWN — Tamika Leslie — Club Growth Director
    // Data preserved but removed from frontend per Melissa's request
    /*
    $flat['tamika-leslie']['answers'] = array(
        'member_since' => 'October 1, 2022',
        'education' => 'Graduated from Glendale High School, Glendale, CA, Class of 2000.',
        'offices' => 'Beaver Club President- 2023-2024. Beaver VPE & VPM- 2024-2025. Beaver VPPR & Secretary- 2025-2026. Area Director 2024-2025. Division B Director 2025-2026.',
        'honors' => 'Triple Crown 2022-2023, 2024-2025.',
        'work_experience' => 'I have managed teams of up to 20 individuals in my retail management career & I have managed hundreds of members as Division B Director.',
        'strategic_planning' => 'Working with my fellow Division Directors & District trio, I have learned how to collaborate and strategically plan how to execute strategies to gain new members at different clubs within my Division.',
        'finance' => 'The experience I have would be best implimented in collaboration with the District finance manager & other trio members to effectively manage the District budget. I believe that a collaborative effort is needed to have harmony & provide input with fellow Toastmasters leadership.',
        'procedures' => 'Developing systems and procedures is my strength. I enjoy discussing the importance of having systems in place to manage current procedures & best practices. This will be an excellent opportunity to use these skills in the merger of District 10 & District 13, to create the newly aligned District 219.',
        'leadership_lessons' => 'The most important and HIGHLY useful lesson I\'ve learned has been how to motivate VOLUNTEERS.',
        'why_serve' => 'It has been my goal since I joined Toastmasters in 2022 to work my way up to District Director. I am following the step-by-step path that I have laid out for myself.',
        'district_objectives' => 'First, to gain more members and to build new clubs in the district. Second, to support the members within each club in the district.',
        'additional_info' => 'I am bilingual in German & English. I am on my way to achieving my Distinguished Toastmaster designation, the only requirement remaining is that I successfully coach Mercer County Toastmasters Club to achieve Distinguished club status. We will achieve this status well before the June 30th, 2026 deadline!',
    );
    */

    // Adam Brown — Division B Director
    $flat['adam-brown']['answers'] = array(
        'member_since' => '2017',
        'education' => 'PI Level 5.',
        'offices' => 'Area Director 2019/2020. President Independently Speaking 2019/2020, President Westlake Toastmasters 2020/2021. VP of Public Relations Independently Speaking 2018/2019, 2024/2025. VP of Membership Independently Speaking 2025/2026. VP of Education Westlake Toastmasters 2019/2020.',
        'honors' => 'Persuasive Influence Pathway Completion. Past Area Director. Recognition Awards: Area Online Ovation Bronze Award Jun. 30, 2020. Recognition Awards: Visiting Victor Award Jun. 30, 2020.',
        'work_experience' => 'Personal Training Business Owner. Business ownership is leadership! Owning a business is much like being a Division Director in that I have led other people to be successful not just in various fitness routines but also in building public relations, growing membership and leading independent contractors to be successful with many tasks of running the business.',
        'strategic_planning' => 'I have completed Quarterly Macro Marketing Plans, Yearly Business Plans, and 90 Day Project Plans.',
        'finance' => 'Running my own business. I have experience with calculating budgets, revenue, profits and expenses.',
        'procedures' => 'I have developed Standard Operating Procedures for my business.',
        'leadership_lessons' => 'I have learned multiple lessons. I have learned that leading by example is one of the most important qualities as people will do what they see the leader doing. Also, Leadership at all levels of Toastmasters starts with the success of each and every Toastmaster.',
        'why_serve' => 'It is an opportunity to serve the Toastmasters community and pay it forward to others. Toastmasters has help me in many more ways than just learning leadership and public speaking and I enjoy helping others in much the same way.',
        'district_objectives' => 'If the District\'s mission is to build new clubs and help them achieve excellence, then the major objectives should be to first focus having a each member succeed. If we accomplish that, then the second focus should be on having healthy membership numbers with our current clubs. Healthy membership numbers happens with a great membership experience combined with a strong public relations effort. If we have both, then we should be able to accomplish the mission of building each existing club and therefore have need to build new clubs as well. I would work to achieve all of this by 1st by encouraging and teaching clubs on how to onboard new members properly and provide all members with a positive experience that also encourages the success of each member. A strong public relations effort can be achieved by many platforms including referrals, a strong social media presence, and even hosting tables at events. This will help grow each club and build new ones as well.',
        'additional_info' => 'I love the outdoors, working out, and spending time with family. I also worked as a Strength and Conditioning Coach in Professional Baseball for 4 years (5 years if you include my internship with the Cleveland Guardians in 2012) from 2013-2016.',
    );

    // Autumn Jose — Division A Director
    $flat['autumn-jose']['answers'] = array(
        'member_since' => '2024',
        'education' => 'Masters Degree - Business Administration/Marketing Concentration, American Intercontinental University.',
        'offices' => 'VPPR - 2024-2025 Western Maryland Toastmasters. President - 2025-2026 Western Maryland Toastmasters. Area 23 Director - 2025-2026.',
        'honors' => 'Western Maryland Toastmasters Toastmaster of the Year - 2024-2025. Triple Crown. Theory of 1 Star. Pathways: PM5; DL4.',
        'work_experience' => 'I have nearly 20 years of marketing and public relations experience in the banking industry, currently serving as AVP, Brand Marketing Specialist at Civista Bank. My role includes brand strategy, internal and external communications, ambassador program, and community engagement. This experience directly supports Toastmasters through strategic messaging, member engagement, storytelling, and increasing visibility for clubs in the district.',
        'strategic_planning' => 'I regularly develop and execute multi-year strategic plans aligned with organizational goals, including brand growth, digital transformation, and engagement initiatives. Within Toastmasters, I have applied this experience through club, area and district planning, focusing on clear goals, measurable outcomes, and sustainable growth.',
        'finance' => 'In my professional role, I manage budgets for brand awareness advertisements and promotional items, I evaluate ROI and make cost-conscious decisions aligned with our strategic plan. In Toastmasters, I work closely with leadership teams to ensure responsible user of resources, transparency, and alignment with district goals.',
        'procedures' => 'I have extensive experience creating processes, procedures, and communication guidelines to ensure consistency and efficiency. This includes developing my ambassador program frameworks, internal communication workflows, repeatable marketing procedures that can be scaled and sustained.',
        'leadership_lessons' => 'I have learned that strong communication, trust and servant leadership are essential. I strive for excellence in all I do, and I believe that starts with listening, empowering others, and leading with clarity and consistency to create engaged teams and overall better outcomes. I also value flexibility, adaptability and continuous learning. Mentorship is essential - even reverse mentorship. No matter your age or skill level, we can all learn from one another.',
        'why_serve' => 'I want to serve as a district leader to help strengthen and elevate our district through clear, consistent, and engaging communication. I am passionate about using my marketing and PR skills to help the district stand out - especially as we merge into a new district - communication will be essential for success. I also want to help support club success and increase member engagement. I have already demonstrated this throughout the development of professional program booklets for multiple D13 events, and I am excited to build on that momentum to further support District 219\'s growth and visibility. I have a unique position - I was born and raised in the D13 area, lived in D10 for 21 years and know a LOT of people, and the areas in Ohio, connected with a lot of key stakeholders in organizations, and am now back in D13 where I reside.',
        'district_objectives' => 'The district\'s major objectives include member growth, club quality, leadership development, and strong communication across all levels. I would support these by strengthening public relation efforts, amplifying success stories, supporting consistent messaging, and collaborating closely with district leaders to ensure alignment and engagement.',
        'additional_info' => 'I am a relationship-driven, servant leader who values collaboration, inclusion, and intentional growth. I bring energy, creativity, and strong work ethic to every role, and I am committed to helping our district thrive. I also have extensive experience in Canva, Adobe Suite, Managing Websites, Developing newsletters including using Flipping Book, creating ads and so much more.',
    );

    // WITHDRAWN — Jolyn Redic — Division A Director
    // Data preserved but removed from frontend per Melissa's request
    /*
    $flat['jolyn-redic']['answers'] = array(
        'member_since' => 'January 1, 2016',
        'education' => 'Graduated High School and a Degree in Real Estate through Hondros College, and a Brokers program through Walsh College.',
        'offices' => 'Attached are offices held. I have served in every officer role since I joined in 2016.',
        'honors' => 'Completed all to receive my DTM in 2018.',
        'work_experience' => 'My professional and volunteer experience focuses on leadership development, communication and mentoring others. Through one of my roles as VPE I have helped members build confidence, develop speaking skills, and grow as leaders. I enjoy supporting people at different stages of their journey and creating an encouraging a learning environment. As a District leader I would focus on strengthening clubs, mentoring leaders, and increasing member engagement.',
        'strategic_planning' => 'I have experience contributing to strategic planning by identifying priorities, setting clear goals, and creating practical steps to achieve them. In Toastmasters this has included focusing on member engagement, leadership development, and strengthening club performance. I believe strategic plans should be clear, focused, and realistic so volunteers can successfully implement them.',
        'finance' => 'I do understand the importance of responsible financial management. My experience includes working with budgets and ensuring resources are used wisely and transparently. My past businesses taught me a great deal about financial responsibility which in turn can relate to how important it is in the District.',
        'procedures' => 'In several leadership roles I have helped create procedures, checklists and guidance that help volunteers clearly understand their responsibilities. Clear and simple procedures help leaders feel confident in their roles and make transitions between leadership teams much easier.',
        'leadership_lessons' => 'One of the most important lessons I have learned is that leadership is about people. When leaders support, listen to, and encourage their teams, success follows. I have also learned that clear communication, appreciation for volunteers, and developing future leaders are essential for long term success. I am extremely high on "recognition" for everything that has been accomplished.',
        'why_serve' => 'Toastmasters has had a meaningful impact on my personal and professional growth. I want to give back by helping others experience the same opportunities. Serving as a District leader allows me to support clubs, mentor leaders, and help create an environment where members can grow and succeed.',
        'district_objectives' => 'The mission of the District is to build new clubs and support all clubs in achieving excellence. I would work to achieve this by strengthening club leadership, supporting Area and other Division Directors, encouraging member engagement and maintaining strong communication across the District.',
        'additional_info' => 'I am passionate about leadership development, mentoring others, and helping members build confidence. I believe in leading with integrity, collaboration, and encouragement. My goal is to support our members, strengthen our clubs, and contribute to a strong and successful District.',
    );
    */

    // Catherine Cullen — Division B Director
    $flat['catherine-cullen']['answers'] = array(
        'member_since' => '2022',
        'education' => 'VC5, MS4, PM3, DL1, Club Coach Program, currently working toward my DTM',
        'offices' => 'Club President 2023-2024, VPE 2022-2023, 2024-2025, 2025-2026. Area 22 Director 2025-2026.',
        'honors' => 'Triple Crown 2024, 2025. International Speech Contest - 3rd place at Division Contest 2025.',
        'work_experience' => 'I was an elementary classroom teacher in the states of WI, IN & MA, which helps me feel confident in sharing information and a true love of learning and growth. I am also a certified Life Coach which supports my strengths in listening and communication. While not work related, I was the oldest of 5 children and learned many leadership and organization skills before I was an adult.',
        'strategic_planning' => 'I only have experience in my personal strategic planning.',
        'finance' => 'My husband works in finance, so I feel like I am surrounded by it, but I do not have any experience.',
        'procedures' => 'I have developed classroom procedures for 2nd graders, but no office experience.',
        'leadership_lessons' => 'I learned that I like fun, people that make things fun and there\'s no reason not to have fun. Sometimes, I\'ve learned ways to extend my patience and try harder to see others\' perspectives. I learned to accept the duality that people are the same everywhere and completely unique and different too. I learned to use my creativity and energy to benefit our club and support the inclusive, encouraging culture. I thought that I was already a person who gives people the benefit of the doubt, but I do have to remind myself that I never know what people are going through. I thought that I was good at asking for help, but I have to continue to work on that too sometimes. I learned that I still have plenty to learn.',
        'why_serve' => 'I like helping. I feel good supporting, guiding, and nurturing others. I see leadership as encouraging others to take a step in the direction of their goals. It is rewarding.',
        'district_objectives' => 'We have a huge change going on for next year. Integrating with another District is a big difference for the District. Highlighting all the things that stay the same (club activities should be the same), showing the advantages of the changes and making things as simple as possible for everyone would be important to me. I would plan well in advance, communicate well in a reliable interval(ie. first Monday of the month), set expectations and follow through with all commitments. I think that the priority has to be coming together - inclusiveness with joy.',
        'additional_info' => 'I joined Toastmasters in 2022 because I wanted to eliminate some of my filler words and polish my public speaking as I worked to grow my new business. In the last 4 years I reached those goals, set and achieved new goals and grew in so many unexpected ways. Toastmasters taught me the importance of evaluation, setting and re-setting goals. With those important life skills, I am a better human.',
    );

    // Megan Rossetti — Division C Director
    $flat['megan-rossetti']['answers'] = array(
        'member_since' => '2024',
        'education' => 'I have an AA from Kent State University.',
        'offices' => '2024/2025 - Club Treasurer 2025/2026 - Club President and Area Director (32).',
        'honors' => '2024/2025 District 10 Above and Beyond award.',
        'work_experience' => 'My background in retail/community banking has prepared me for this position. The requirement to think strategically, to have vision that can be executed upon by leaning into the strengths of the brand, and managing diverse groups of people both locally and remotely are all experiences I will bring in because of having worked at banks.',
        'strategic_planning' => 'My last role at the bank was heavily engrossed in long term strategic planning tied directly to the success of the deposit, investment, and private banking divisions. During this time I also became a Prosci Certified Change Practitioner. I personally love change. It is always happening. Change requires a strong understanding of where we have come from, how we got to our current point, as well as, the why and how of where we are headed.',
        'finance' => 'I have managed retail bank branches through a bank specific profit and loss structure. I feel great about balancing a checkbook and recommending the appropriate product to meet the needs of a client. I am not an expert in accounting or business finance.',
        'procedures' => 'In addition to my strategic role, I worked closely with the product development, compliance, and technical departments at the bank to bring new products to market. During this process developing process and procedures that aligned to the needs of multiple departments and met all legal standards was visited and revisited to ensure it was done correctly.',
        'leadership_lessons' => 'Prepartion is the key to success! People perform better when expectations are explicit. Don\'t be afraid to ask more than once and in many different ways.',
        'why_serve' => 'It would be an honor to serve as a leader during the formation of D219. I believe in the legacy and brand of Toastmasters, but I understand that what got us to this place 100 years in will not sustain us for the next 100 years. We need to be agile. We must meet members and our communities as they grow and change. I want to be a part of that. I want to help build the next 100 years of legacy for our members. And that starts in my club, in my division, and in D219.',
        'district_objectives' => '"We build new clubs and support all clubs in achieving excellence." New Clubs - Banking often requires managers to go into the community and cold call on small businesses. The strategies are very transferrable to growing clubs. Clubs achieving excellence - I know and understand that every club has its own culture, but I also am a believer in adhering to brand standards. I think as we continue educate and align clubs to truly embody the brand, all success metrics will rise in kind.',
        'additional_info' => '',
    );

    // James Leslie — Division D Director
    $flat['james-leslie']['answers'] = array(
        'member_since' => '08/2024',
        'education' => 'Hazardous Waste Operations and Emergency Response - Slippery Rock - 2002.',
        'offices' => 'Club Secretary 24/25. Club President 25/26.',
        'honors' => 'Persuasive Influence pathway complete. Triple play recognition. Facilitated a speech craft.',
        'work_experience' => 'In my experience the following titles that would be applicable to this position: Project Manager, Project Lead, Operations Manager, Sales/Operations Manager. I have overseen projects consisting of multiple teams and multiple subcontractors while reporting directly to the project superintendent and stakeholders. I have lengthy experience of systems, tools and strategic planning from concept to implementation, through long term usage.',
        'strategic_planning' => 'I have been involved in - project planning for construction projects up into years long projects - strategic planning for company growth and expansion - creation of programs for safety, sales, sales training, internal talent development, marketing, advertising.',
        'finance' => 'The above experience in construction management also required the aspects of finance contained within.',
        'procedures' => 'My creation of safety programs and sales training would be the most procedural things that I have developed.',
        'leadership_lessons' => 'Leadership is a selfless endeavor. Your goals are always most easily achieved by helping those around you to lift themselves to their highest potential. This must be guided by the proper systems and tools.',
        'why_serve' => 'I feel as though I have something valuable to contribute to toastmasters.',
        'district_objectives' => 'Objective 1: Growth and recruitment. This always needs to be top of mind. We need to drive this through consistent messaging and directives. 2: Navigation of the realignment. This is going to have to be achieved in real time with proper planning and execution of the challenges that we see coming as well as the challenges that will present themselves in this endeavor that will inevitably crop up unexpectedly.',
        'additional_info' => 'I can be contacted at any time with further questions.',
    );

    // Stuart Strickland — Division E Director
    $flat['stuart-strickland']['answers'] = array(
        'member_since' => 'April 2008',
        'education' => 'B.A., SUNY Geneseo, 1981. M.S. Information Science, University of Pittsburgh, 2001.',
        'offices' => 'Area 31 Director, FY15 (+- a year). Area 32 Director, FY26. VP Education, Beacon club #672 (current).',
        'honors' => 'DTM, 2019.',
        'work_experience' => 'PNC Bank, run-the-bank team, senior software engineer. Much of my work was to coach and mentor junior and new-to-team engineers on how the larger software process worked and how their work contributed to keeping everything working smoothly.',
        'strategic_planning' => 'Two specific tasks: One, semi-annual fail-over planning. This involved working with teams of people in my own and several related departments, to ensure that computer processes were transfered to a parallel data center without error or delay. These regularly required 30 to 40 steps and coordination among 15 people, and had to be completed in only a couple of hours without significant error. Two, renewal of SSL security certificates for websites. My job was to convey to a team of 25 to 30 software engineers how to renew, install and test these certificates so that websites and programs would continue to operate. Failure was unacceptable, as were delays of more than an hour.',
        'finance' => 'To be honest, not much beyond my own personal accounts. I\'ve never bounced a check, though, and have a FICO score above 800.',
        'procedures' => 'At the bank, I was responsible for determining cause-of-the-cause problem investigation and recommending (and often implementing) solutions to ensure the issues would not arise again.',
        'leadership_lessons' => 'Being Area Director the second time around was easier than the first. Being VPE the second time around was easier than the first. The Beacon club is large, over 20 members, with a good shot at hitting Smedley Distinguished by June 30 2026. Much of this success is due to being able to compare efforts in previous clubs in which I was a member as well as those I currently manage as Area 32 Director.',
        'why_serve' => 'Moving up to Division Director seemed a natural step after being Area Director this past year.',
        'district_objectives' => 'I see a District\'s objectives as ensuring that the clubs within its purview all succeed, said success being a measure of how well each one\'s members are succeeding at what they joined to achieve. If a club is hitting Distinguished, then it is serving its members well. If a significant number of a Division or District\'s clubs are achieving Distinguished, then the District is doing its job well, and if they are not, it\'s the District\'s responsibility to provide the resources to help them get there.',
        'additional_info' => 'I recently retired so now have more bandwidth to handle Toastmasters responsibilities. My main personal goal within Toastmasters is to achieve my second DTM, exclusively within the Pathways program, by June 30 2027. I am at PM4 and VC3 at the moment.',
    );

    // WITHDRAWN — Cosmas Nwakanma — Division F Director
    // Data preserved but removed from frontend per Melissa's request
    /*
    $flat['cosmas-nwakanma']['answers'] = array(
        'member_since' => 'January 1, 2022',
        'education' => 'PhD IT-Convergence Engineering, 2022 (Kumoh National Institute of Technology, South Korea). MBA Project Management Technology, 2016 (Federal University of Technology, Warri, Nigeria). MSc Information Technology, 2012 (Federal University of Technology, Warri, Nigeria). B.Eng. Communication Engineering, 2005 (Federal University of Technology, Warri, Nigeria).',
        'offices' => '1. Area 24 Director: Division C, District 13, July 1, 2025 - June 30, 2026. 2. Club VPE: Laurel Highlands Toastmasters Club July 1, 2025 - June 30, 2026. 3. Club VPM: Country Roads Toastmasters Club June 1, 2025 - June 30, 2026. 4. Club President: Daegu Toastmasters Club - SOUTH KOREA July 1 2024 - September 30, 2024. 5. Club VPM: Daegu Toastmasters Club - SOUTH KOREA July 1, 2023 - June 30, 2024. 6. Club Secretary: Daegu Toastmasters Club - SOUTH KOREA July 1, 2022 - June 30, 2023.',
        'honors' => '1. Club Sponsor: June 23, 2025 Country Roads Toastmasters Club. 2. Country Roads Toasters Club had all 7 Leaders trained twice in record time. 3. Innovative Planning Level 5 completion. 4. Co-Chair, Spring 2026 Conference (District 13).',
        'work_experience' => 'My experience in banking, research, and academia has prepared me to serve effectively as a Division Director. As a Senior Banking Assistant, I developed organizational, financial, and process management skills, supporting accountability and operational excellence. As a Senior Research Scientist, I led complex projects and applied data-driven decision-making, reflecting strategic planning and results orientation. My work as a Postdoctoral Researcher strengthened my ability to mentor, collaborate, and manage priorities, aligning with team building and leadership development. As a University Lecturer and Project Supervisor, I coached and guided students through complex projects, demonstrating the competencies of developing leaders and empowering others. These experiences equip me to lead with strategic focus, structured processes, and a commitment to fostering capable, motivated Toastmasters leaders across the Division.',
        'strategic_planning' => 'As a project manger, my first interest in the pathway was innovative planning as it aligned with my core strength in stragegic planning. I have experience in strategic planning through both my education and leadership roles. In my MBA in Project Management, I was trained in setting strategic objectives, aligning initiatives with organizational goals, and developing action plans with measurable outcomes. In leadership roles, I have applied this by assessing current performance, identifying gaps, setting priorities, and working with teams to translate strategy into achievable plans. I also focus on tracking progress and adjusting plans as needed to ensure results. Outside Toastmasters, I am currently a stretgic leader in IEEE as aSenior Member of IEEE. As a researcher, I applied my strategic planning skills in ensuring that multi million dollar finded projects are delivaered on time, within budget and with optimal results.',
        'finance' => 'My experience in finance is primarily applied and managerial rather than technical accounting. Through my MBA in Project Management, I developed a strong foundation in budgeting, cost control, financial planning, and performance tracking. I have applied these skills in leadership roles by managing budgets, monitoring expenses against plans, and making data-driven decisions to ensure resources are used effectively. I am comfortable reviewing financial reports, asking the right questions, and ensuring financial accountability and transparency. As a leader, my focus is on responsible stewardship of funds and aligning financial decisions with organizational goals. I have a three (3) years working experience in the larget bank in Nigeria.',
        'procedures' => 'I have solid experience developing and improving procedures through my leadership roles. In Toastmasters, I have worked with clubs and teams to turn goals into clear, repeatable processes. Whether that\'s planning events, tracking performance, or supporting officers. I focus on understanding what already exists, identifying gaps, and then documenting simple, practical steps that people can actually follow. I also make sure procedures are communicated clearly and adjusted based on feedback, so they support consistency while still allowing flexibility. As Division Director, I would apply this same approach to ensure alignment with District goals and smooth coordination across areas and clubs. As a University lecturer and researche for 17 years, this comes naturally for me.',
        'leadership_lessons' => '1. A leader is not the one who works or walks alone. As you lead, watch to ensure your followers are still behind you. 2. Sponsoring a new club in Morgantown taught me that people see and know a good leader who leads by example and transparency.',
        'why_serve' => 'Two reasons. 1. Professionally, I have grown to be better person through numerous years of service to the public. That will continue all my life time. 2. Toastmasters has gave me so much in terms of capacity development and district 13 helped me settle down when I arrived the United States in 2024. I promise to pay back by serving in any little way I can to support Toastmasters. I am confidet that the district will take advantage of my background and capacity to manage human and natral resources in any capacity they deem fit.',
        'district_objectives' => 'As we transition to district 219, my focus will be to hold the clubs assigned together in unity, radiating support for one another and endearing new members to the clubs. If I can achieve the inter and intra club relationships, I would have helped set the foundation for a strong future for district 219.',
        'additional_info' => 'I am a servant leader who is always ready to lead by serving the people. People love to be served and when I serve them, they naturally allow me to lead the way. It has worked for me for over 3 decades.',
    );
    */

    return $flat;
}

// =============================================================================
// GITHUB AUTO-UPDATER
// =============================================================================

class D219_GitHub_Updater {
    private $slug, $plugin_data, $github_response;
    private $rollback_was_active = false;
    public function __construct($f) {
        $this->slug = plugin_basename($f);
        add_filter('pre_set_site_transient_update_plugins', array($this, 'check_update'));
        add_filter('plugins_api', array($this, 'plugin_info'), 20, 3);
        // Temporarily deactivate WP Rollback during our updates (it crashes without ZipArchive)
        add_filter('upgrader_pre_install', array($this, 'pre_install_disable_rollback'), 10, 2);
        add_filter('upgrader_post_install', array($this, 'post_install_enable_rollback'), 10, 3);
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
        $message .= "Update from your WordPress Dashboard:\n";
        $message .= admin_url('plugins.php') . "\n";
        $message .= "Look for the update notice under \"District 219 Transition Page\"\n\n";
        $message .= "GitHub release page:\n";
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
        return (object) array('name' => $pd['Name'], 'slug' => dirname($this->slug), 'version' => ltrim($rel->tag_name, 'v'), 'author' => $pd['Author'], 'homepage' => $pd['PluginURI'], 'sections' => array('description' => $pd['Description'], 'changelog' => wp_kses_post(nl2br(esc_html($rel->body)))), 'download_link' => $rel->zipball_url);
    }
    // Clean up notification flags when update completes
    public function after_update() {
        global $wpdb;
        $wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->options} WHERE option_name LIKE %s", $wpdb->esc_like('d219_update_notified_') . '%'));
    }
    // Temporarily deactivate WP Rollback before our plugin update
    public function pre_install_disable_rollback($response, $hook_extra) {
        if (!isset($hook_extra['plugin']) || $hook_extra['plugin'] !== $this->slug) return $response;
        if (!function_exists('is_plugin_active')) include_once ABSPATH . 'wp-admin/includes/plugin.php';
        $rollback_slug = 'wp-rollback/wp-rollback.php';
        if (is_plugin_active($rollback_slug)) {
            $this->rollback_was_active = true;
            deactivate_plugins($rollback_slug, true); // silent deactivation
        }
        return $response;
    }
    // After install: rename extracted folder to expected plugin slug & reactivate WP Rollback
    public function post_install_enable_rollback($response, $hook_extra, $result) {
        if (!isset($hook_extra['plugin']) || $hook_extra['plugin'] !== $this->slug) return $response;

        // Rename the extracted folder (GitHub zipball creates cameronsuorsa-d219-transition-page-HASH/)
        // WordPress expects the folder to be d219-transition-page/
        global $wp_filesystem;
        $proper_dir = WP_PLUGIN_DIR . '/' . dirname($this->slug) . '/';
        if (isset($result['destination']) && $result['destination'] !== $proper_dir) {
            $wp_filesystem->move($result['destination'], $proper_dir);
            $result['destination'] = $proper_dir;
            $result['destination_name'] = dirname($this->slug);
            $result['remote_destination'] = $proper_dir;
        }

        // Reactivate WP Rollback if it was deactivated
        if ($this->rollback_was_active) {
            $rollback_slug = 'wp-rollback/wp-rollback.php';
            activate_plugin($rollback_slug);
            $this->rollback_was_active = false;
        }

        return $result;
    }
}
if (is_admin()) new D219_GitHub_Updater(D219_TRANSITION_PLUGIN_FILE);

// =============================================================================
// REWRITE RULES
// =============================================================================

add_action('init', function() {
    add_rewrite_rule('^transition/?$', 'index.php?d219_transition=1', 'top');
    add_rewrite_rule('^dlc/?$', 'index.php?d219_dlc=1', 'top');
    add_rewrite_rule('^staging/transition/?$', 'index.php?d219_transition=1&d219_staging=1', 'top');
    add_rewrite_rule('^staging/dlc/?$', 'index.php?d219_dlc=1&d219_staging=1', 'top');
    add_rewrite_rule('^candidates/?$', 'index.php?d219_profiles=1', 'top');
    add_rewrite_rule('^staging/candidates/?$', 'index.php?d219_profiles=1&d219_staging=1', 'top');
    add_rewrite_rule('^staging/email/?$', 'index.php?d219_email=1&d219_staging=1', 'top');
    add_rewrite_tag('%d219_transition%', '([^&]+)');
    add_rewrite_tag('%d219_dlc%', '([^&]+)');
    add_rewrite_tag('%d219_profiles%', '([^&]+)');
    add_rewrite_tag('%d219_email%', '([^&]+)');
    add_rewrite_tag('%d219_staging%', '([^&]+)');
});

// Force plugin query vars even when a WordPress page with matching slug exists.
// Without this, WP resolves /transition to its own page object and ignores our rewrite.
add_action('parse_request', function($wp) {
    $path = trim($wp->request, '/');
    $map = array(
        'transition'          => array('d219_transition' => '1'),
        'dlc'                 => array('d219_dlc' => '1'),
        'staging/transition'  => array('d219_transition' => '1', 'd219_staging' => '1'),
        'staging/dlc'         => array('d219_dlc' => '1', 'd219_staging' => '1'),
        'candidates'          => array('d219_profiles' => '1'),
        'staging/candidates'  => array('d219_profiles' => '1', 'd219_staging' => '1'),
        'staging/email'       => array('d219_email' => '1', 'd219_staging' => '1'),
    );
    if (isset($map[$path])) {
        $wp->query_vars = $map[$path];
    }
});

// Auto-flush rewrite rules when plugin version changes (handles updates)
add_action('init', function() {
    $stored_version = get_option('d219_transition_version');
    if ($stored_version !== D219_TRANSITION_VERSION) {
        flush_rewrite_rules();
        update_option('d219_transition_version', D219_TRANSITION_VERSION);
    }
}, 20);
register_activation_hook(__FILE__, function() {
    add_rewrite_rule('^transition/?$', 'index.php?d219_transition=1', 'top');
    add_rewrite_rule('^dlc/?$', 'index.php?d219_dlc=1', 'top');
    add_rewrite_rule('^staging/transition/?$', 'index.php?d219_transition=1&d219_staging=1', 'top');
    add_rewrite_rule('^staging/dlc/?$', 'index.php?d219_dlc=1&d219_staging=1', 'top');
    add_rewrite_rule('^candidates/?$', 'index.php?d219_profiles=1', 'top');
    add_rewrite_rule('^staging/candidates/?$', 'index.php?d219_profiles=1&d219_staging=1', 'top');
    add_rewrite_rule('^staging/email/?$', 'index.php?d219_email=1&d219_staging=1', 'top');
    add_rewrite_tag('%d219_transition%', '([^&]+)');
    add_rewrite_tag('%d219_dlc%', '([^&]+)');
    add_rewrite_tag('%d219_profiles%', '([^&]+)');
    add_rewrite_tag('%d219_email%', '([^&]+)');
    add_rewrite_tag('%d219_staging%', '([^&]+)');
    flush_rewrite_rules();
    update_option('d219_transition_version', D219_TRANSITION_VERSION);
});
register_deactivation_hook(__FILE__, function() { 
    flush_rewrite_rules(); 
    delete_option('d219_transition_version');
});

// =============================================================================
// DIRECT TEMPLATE TAKEOVER — priority 0 on template_redirect
// Fires before Elementor, theme, or any other template system.
// Checks raw URL, includes our template, and exits. Nothing can override this.
// =============================================================================
add_action('template_redirect', function() {
    $raw = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
    $home = trim(parse_url(home_url(), PHP_URL_PATH), '/');
    $path = $home ? preg_replace('#^' . preg_quote($home, '#') . '/#', '', $raw) : $raw;
    $path = rtrim($path, '/');

    $map = array(
        'transition'          => 'template-transition.php',
        'dlc'                 => 'template-dlc-candidates.php',
        'staging/transition'  => 'redirect:/transition',
        'staging/dlc'         => 'redirect:/dlc',
        'candidates'          => 'redirect:/dlc',
        'staging/candidates'  => 'redirect:/dlc',
        'staging/email'       => 'template-email-preview.php',
    );

    if (!isset($map[$path])) return;

    $action = $map[$path];

    // Email preview is admin-only
    if ($path === 'staging/email' && !current_user_can('manage_options')) {
        wp_redirect(home_url('/'), 302);
        exit;
    }

    // Handle redirects
    if (strpos($action, 'redirect:') === 0) {
        wp_redirect(home_url(substr($action, 9)), 301);
        exit;
    }

    // Serve the template directly and exit — nothing else runs after this
    $file = D219_TRANSITION_PLUGIN_DIR . $action;
    if (file_exists($file)) {
        // Set query var so templates can detect staging context
        set_query_var('d219_staging', strpos($path, 'staging/') === 0 ? 1 : 0);
        include $file;
        exit;
    }
}, 0);

// =============================================================================
// ENQUEUE STYLES
// =============================================================================

// Enqueue external styles (Google Fonts, Font Awesome) — only on plugin pages
add_action('wp_enqueue_scripts', function() {
    $is_plugin_page = get_query_var('d219_transition') || get_query_var('d219_dlc') || get_query_var('d219_profiles');
    if ($is_plugin_page) {
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
    $is_plugin_page = get_query_var('d219_transition') || get_query_var('d219_dlc') || get_query_var('d219_profiles');

    if ($is_plugin_page) {
        // Full CSS on /transition and /dlc pages
        $css_file = D219_ASSETS_DIR . 'transition-styles.css';
        if (file_exists($css_file)) {
            $css = file_get_contents($css_file);
            $css = str_replace('</style', '<\\/style', $css); // Prevent style tag breakout
            echo "\n<style id=\"d219-transition-styles\">\n";
            echo $css;
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
    if (get_query_var('d219_transition') || get_query_var('d219_dlc') || get_query_var('d219_profiles')) {
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
        <?php $banner_mode = get_query_var('d219_staging') ? 'candidates' : D219_DLC_MODE; ?>
        <div class="d219-transition-banner">
            <?php if ($banner_mode === 'candidates') : ?>
            <a href="<?php echo esc_url(d219_page_url('transition')); ?>">Transition</a>: D10 &amp; D13 merge to become <span class="d219-banner-219">D219</span> on July 1st. <a href="<?php echo esc_url(d219_page_url('dlc')); ?>">Meet the Candidates</a> — Election April 27th.
            <?php else : ?>
            <a href="<?php echo esc_url(d219_page_url('transition')); ?>">Transition</a>: D10 &amp; D13 merge to become <span class="d219-banner-219">D219</span> on July 1st. <a href="<?php echo esc_url(d219_page_url('dlc')); ?>">DLC Nominations</a> close Feb 25th.
            <?php endif; ?>
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
                window.location.replace('<?php echo esc_js(d219_page_url('dlc')); ?>');
            }
        })();
        </script>
        <?php
    }
});

// =============================================================================
// PLUGIN ACTION LINKS (Plugins page)
// =============================================================================

add_filter('plugin_action_links_' . plugin_basename(__FILE__), function($links) {
    $site = home_url();

    // Production links (always available — these pages are already live)
    $prod = array();
    $prod[] = '<a href="' . esc_url($site . '/transition') . '">Transition</a>';
    $prod[] = '<a href="' . esc_url($site . '/dlc') . '">DLC</a>';
    $links[] = '<strong>Production:</strong> ' . implode(' &middot; ', $prod);

    // Staging links (always available)
    $stage = array();
    $stage[] = '<a href="' . esc_url($site . '/staging/transition') . '">Transition</a>';
    $stage[] = '<a href="' . esc_url($site . '/staging/dlc') . '">DLC</a>';
    $stage[] = '<a href="' . esc_url($site . '/staging/email') . '">Email</a>';
    $links[] = '<strong>Staging:</strong> ' . implode(' &middot; ', $stage);

    return $links;
});
