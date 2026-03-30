<?php
/**
 * Template for District 219 DLC — Candidate Showcase with Browse, Compare & By Question
 * Integrated interactive tool for exploring candidates without downloading PDFs
 */

if (!defined('ABSPATH')) exit;

$candidates_url = D219_ASSETS_URL . 'candidates/';
$roles = d219_get_candidates();
$bios = d219_get_candidate_bios();
$committees = d219_get_committees();
$dlc = $committees['leadership'];
$headshots_url = D219_ASSETS_URL . 'headshots/';

// Question labels
$questions = array(
    'showcase_video' => 'Candidate Showcase Video',
    'member_since' => 'Toastmasters Member Since',
    'education' => 'Education',
    'offices' => 'Toastmasters Offices Held',
    'honors' => 'Honors & Recognition',
    'work_experience' => 'Relevant Work Experience',
    'strategic_planning' => 'Strategic Planning Experience',
    'finance' => 'Finance Experience',
    'procedures' => 'Developing Procedures Experience',
    'leadership_lessons' => 'Lessons from Leadership',
    'why_serve' => 'Why Serve as a District Leader?',
    'district_objectives' => 'District Mission Objectives',
    'additional_info' => 'Additional Information',
);

// Build JSON for JS
$candidates_json = array();
foreach ($bios as $slug => $bio) {
    $candidates_json[] = array(
        'slug' => $slug,
        'name' => $bio['name'],
        'credentials' => $bio['credentials'],
        'role' => $bio['role'],
        'type' => $bio['type'],
        'region' => $bio['region'],
        'photo' => $candidates_url . 'photos/' . $bio['photo'],
        'bio_pdf' => $bio['bio_pdf'] ? $candidates_url . 'bios/' . $bio['bio_pdf'] : null,
        'video' => $bio['video'],
        'answers' => $bio['answers'],
        'all_role_slugs' => array_map('sanitize_title', array_map('trim', explode('&', $bio['role']))),
    );
}

get_header();
?>
<div class="d219-transition-page d219-dlc-page d219-page">

    <!-- Hero -->
    <section class="d219-hero d219-hero-dlc">
        <div class="d219-container">
            <h1 class="d219-title">District 219 Candidates</h1>
            <p class="d219-subtitle">Nominations are closed &mdash; the candidate slate has been announced</p>
            <p class="d219-hero-note">Browse profiles, compare responses side by side, or explore by question<br>
            <em>Candidates listed in alphabetical order by last name &middot; Candidate Showcase videos will be added after April 22, 2026</em></p>
            <div class="d219-hero-nominations">
                <p><i class="fa-solid fa-calendar-check"></i> <strong>Election Meeting:</strong> April 27, 2026 &middot; 7:00 PM via Zoom</p>
            </div>
        </div>
    </section>

    <!-- DLC Nomination Report -->
    <section class="d219-candidates-intro-section">
        <div class="d219-container">
            <?php $dlc_chair = $committees['leadership']; ?>
            <div class="d219-committee-report">
                <div class="d219-committee-report-header">
                    <i class="fa-solid fa-file-lines"></i>
                    <div>
                        <h4>District Leadership Committee Nomination Report</h4>
                        <span>District 219 &middot; 2026&ndash;2027 Toastmasters Year</span>
                    </div>
                </div>
                <div class="d219-committee-report-body">
                    <p>The District Leadership Committee for District 219 is pleased to announce the nominated candidates for District Office for the <strong>2026&ndash;2027 Toastmasters year</strong>. These individuals have stepped forward to lead our newly forming district, created from <a href="https://district10.org/" target="_blank" rel="noopener">Districts 10</a>, <a href="https://d13tm.com/" target="_blank" rel="noopener">13</a> and <a href="https://d38tm.org/" target="_blank" rel="noopener">38</a>.</p>

                    <p>Elections will be held at the <strong>District 219 Business Meeting on April 27, 2026</strong>.</p>

                    <p><em>Candidates are listed in alphabetical order by last name. One candidate, Autumn Jose, has been nominated for two roles: Division A Director and Division F Director.</em></p>
                </div>
                <div class="d219-committee-report-sig">
                    <img src="<?php echo esc_url(D219_ASSETS_URL . 'headshots/' . $dlc_chair['photo']); ?>" alt="<?php echo esc_attr($dlc_chair['name']); ?>">
                    <div class="d219-sig-info">
                        <span class="d219-sig-name"><?php echo esc_html(explode(',', $dlc_chair['name'])[0]); ?></span>
                        <span class="d219-sig-title"><?php echo esc_html($dlc_chair['title']); ?></span>
                        <span class="d219-sig-contact"><a href="mailto:<?php echo antispambot($dlc_chair['email']); ?>"><?php echo antispambot($dlc_chair['email']); ?></a></span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- View Tabs + Filters (sticky) -->
    <section class="d219-profiles-nav-section" id="views">
        <div class="d219-container">
            <div class="d219-profiles-tabs">
                <button class="d219-ptab d219-ptab-active" data-view="browse"><i class="fa-solid fa-user"></i> <span>Browse</span></button>
                <button class="d219-ptab" data-view="compare"><i class="fa-solid fa-table-columns"></i> <span>Compare</span></button>
                <button class="d219-ptab" data-view="question"><i class="fa-solid fa-list-check"></i> <span>By Question</span></button>
            </div>
            <div class="d219-profiles-filter">
                <button class="d219-pfilt d219-pfilt-active" data-filter="all">All</button>
                <button class="d219-pfilt" data-filter="district-director">DD</button>
                <button class="d219-pfilt" data-filter="program-quality-director">PQD</button>
                <button class="d219-pfilt" data-filter="club-growth-director">CGD</button>
                <button class="d219-pfilt d219-pfilt-div" data-filter="division-a-director">Div A</button>
                <button class="d219-pfilt d219-pfilt-div" data-filter="division-b-director">Div B</button>
                <button class="d219-pfilt d219-pfilt-div" data-filter="division-c-director">Div C</button>
                <button class="d219-pfilt d219-pfilt-div" data-filter="division-d-director">Div D</button>
                <button class="d219-pfilt d219-pfilt-div" data-filter="division-e-director">Div E</button>
                <button class="d219-pfilt d219-pfilt-div" data-filter="division-f-director">Div F</button>
            </div>
        </div>
    </section>

    <!-- ============ BROWSE VIEW ============ -->
    <section class="d219-view d219-view-browse d219-view-active" id="view-browse">
        <div class="d219-container">

            <?php foreach ($roles as $role) :
                $role_slug = sanitize_title($role['role']);
                $type_class = $role['type'];
            ?>
            <div class="d219-role-browse-group" data-role-slug="<?php echo esc_attr($role_slug); ?>" data-type="<?php echo esc_attr($type_class); ?>">
                <h2 class="d219-role-browse-title">
                    <?php echo esc_html($role['role']); ?>
                    <?php if (!empty($role['region'])) : ?> <span class="d219-role-region">(<?php echo esc_html($role['region']); ?>)</span><?php endif; ?>
                </h2>
                <div class="d219-browse-cards">
                    <?php foreach ($role['candidates'] as $c) :
                        $slug = sanitize_title(explode(',', $c['name'])[0]);
                        $bio = isset($bios[$slug]) ? $bios[$slug] : null;
                        if (!$bio) continue;
                        $has_answers = !empty($bio['answers']);
                    ?>
                    <div class="d219-profile-card" id="<?php echo esc_attr($slug); ?>" data-slug="<?php echo esc_attr($slug); ?>">
                        <div class="d219-profile-card-top">
                            <img src="<?php echo esc_url($candidates_url . 'photos/' . $bio['photo']); ?>" alt="<?php echo esc_attr($bio['name']); ?>" class="d219-profile-photo-lg">
                            <div class="d219-profile-card-info">
                                <h3><?php echo esc_html($bio['name']); ?></h3>
                                <p class="d219-profile-creds"><?php echo esc_html($bio['credentials']); ?></p>
                                <p class="d219-profile-role">Candidate for <?php echo esc_html($role['role']); ?>
                                    <?php if (!empty($role['region'])) : ?> <span class="d219-profile-region">(<?php echo esc_html($role['region']); ?>)</span><?php endif; ?>
                                </p>
                                <?php if (strpos($bio['role'], '&') !== false) : ?>
                                <p class="d219-profile-dual-note"><i class="fa-solid fa-circle-info"></i> Also candidate for <?php
                                    // Show the OTHER role
                                    $all_roles = array_map('trim', explode('&', $bio['role']));
                                    foreach ($all_roles as $r) {
                                        if (trim($r) !== $role['role']) echo esc_html($r);
                                    }
                                ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="d219-profile-actions">
                            <?php if ($has_answers) : ?>
                            <button class="d219-btn-sm d219-btn-expand" data-slug="<?php echo esc_attr($slug); ?>"><i class="fa-solid fa-chevron-down"></i> View Responses</button>
                            <?php endif; ?>
                            <?php if ($bio['video']) : ?>
                            <button class="d219-btn-sm d219-btn-video" data-slug="<?php echo esc_attr($slug); ?>"><i class="fa-solid fa-video"></i> Video</button>
                            <?php endif; ?>
                            <?php if ($bio['bio_pdf']) : ?>
                            <a href="<?php echo esc_url($candidates_url . 'bios/' . $bio['bio_pdf']); ?>" class="d219-btn-sm d219-btn-pdf" target="_blank" rel="noopener"><i class="fa-solid fa-file-pdf"></i> PDF</a>
                            <?php else : ?>
                            <span class="d219-btn-sm d219-btn-disabled"><i class="fa-solid fa-file-pdf"></i> PDF Coming Soon</span>
                            <?php endif; ?>
                        </div>
                        <!-- Video -->
                        <div class="d219-profile-video" data-slug="<?php echo esc_attr($slug); ?>">
                            <?php if ($bio['video']) : ?>
                            <div class="d219-video-embed"><?php echo $bio['video']; ?></div>
                            <?php else : ?>
                            <div class="d219-video-placeholder">
                                <i class="fa-solid fa-video"></i>
                                <span>Candidate Showcase Video &mdash; Available after April 22, 2026</span>
                            </div>
                            <?php endif; ?>
                        </div>
                        <!-- Expandable Q&A -->
                        <?php if ($has_answers) : ?>
                        <div class="d219-profile-qa" data-slug="<?php echo esc_attr($slug); ?>">
                            <?php foreach ($questions as $key => $label) :
                                if ($key === 'showcase_video') continue;
                                $answer = isset($bio['answers'][$key]) ? $bio['answers'][$key] : '';
                                if (empty($answer)) continue;
                            ?>
                            <div class="d219-qa-item">
                                <h4><?php echo esc_html($label); ?></h4>
                                <p><?php echo esc_html($answer); ?></p>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- ============ COMPARE VIEW ============ -->
    <section class="d219-view d219-view-compare" id="view-compare">
        <div class="d219-container">
            <p class="d219-compare-hint">Select up to 3 candidates to view or compare their responses side by side.</p>
            <div class="d219-compare-picker" id="compare-picker">
                <?php foreach ($bios as $slug => $bio) : ?>
                <label class="d219-compare-chip" data-type="<?php echo esc_attr($bio['type']); ?>" data-role-slugs="<?php echo esc_attr(implode(' ', array_map('sanitize_title', array_map('trim', explode('&', $bio['role']))))); ?>">
                    <input type="checkbox" value="<?php echo esc_attr($slug); ?>" class="d219-compare-cb">
                    <img src="<?php echo esc_url($candidates_url . 'photos/' . $bio['photo']); ?>" alt="">
                    <span class="d219-chip-name"><?php echo esc_html($bio['name']); ?></span>
                    <span class="d219-chip-role"><?php
                        // Short role labels for compact chips
                        $short_roles = array(
                            'District Director' => 'DD',
                            'Program Quality Director' => 'PQD',
                            'Club Growth Director' => 'CGD',
                            'Division A Director' => 'Div A',
                            'Division B Director' => 'Div B',
                            'Division C Director' => 'Div C',
                            'Division D Director' => 'Div D',
                            'Division E Director' => 'Div E',
                            'Division F Director' => 'Div F',
                        );
                        $chip_roles = array_map('trim', explode('&', $bio['role']));
                        $chip_short = array();
                        foreach ($chip_roles as $cr) {
                            $chip_short[] = isset($short_roles[$cr]) ? $short_roles[$cr] : $cr;
                        }
                        echo esc_html(implode(' & ', $chip_short));
                    ?></span>
                </label>
                <?php endforeach; ?>
            </div>
            <div class="d219-compare-table-wrap" id="compare-table-wrap">
                <p class="d219-compare-empty">Select candidates above to view and compare responses.</p>
            </div>
        </div>
    </section>

    <!-- ============ BY QUESTION VIEW ============ -->
    <section class="d219-view d219-view-question" id="view-question">
        <div class="d219-container">
            <div class="d219-question-controls d219-question-sticky">
                <div class="d219-question-picker">
                    <label for="d219-q-select">Question:</label>
                    <select id="d219-q-select">
                        <?php
                        // Default to member_since until at least one candidate has a video
                        $any_video = false;
                        foreach ($bios as $b) { if (!empty($b['video'])) { $any_video = true; break; } }
                        $default_q = $any_video ? 'showcase_video' : 'member_since';
                        foreach ($questions as $key => $label) : ?>
                        <option value="<?php echo esc_attr($key); ?>"<?php if ($key === $default_q) echo ' selected'; ?>><?php echo esc_html($label); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="d219-question-results" id="question-results">
                <!-- JS fills this -->
            </div>
        </div>
    </section>

    <!-- Election Info -->
    <section class="d219-candidates-election-section">
        <div class="d219-container">
            <div class="d219-election-card">
                <div class="d219-election-icon"><i class="fa-solid fa-check-to-slot"></i></div>
                <div class="d219-election-details">
                    <h3>District 219 District Council Meeting</h3>
                    <p class="d219-election-date">Monday, April 27, 2026 &middot; 7:00 &ndash; 9:00 PM Eastern via Zoom</p>
                </div>
                <?php $zoom_link = defined('D219_ZOOM_LINK') ? D219_ZOOM_LINK : ''; ?>
                <?php if (!empty($zoom_link)) : ?>
                <a href="<?php echo esc_url($zoom_link); ?>" class="d219-btn d219-btn-primary" target="_blank" rel="noopener">
                    <i class="fa-solid fa-calendar-check"></i> Register Now
                </a>
                <?php endif; ?>
            </div>
            <?php $meetings_url = D219_ASSETS_URL . 'meetings/'; ?>
            <div class="d219-election-docs">
                <a href="<?php echo esc_url($meetings_url . 'District-219-Council-Meeting-Notification.pdf'); ?>" class="d219-btn d219-btn-download" target="_blank"><i class="fa-solid fa-file-pdf"></i> Meeting Notification</a>
                <a href="<?php echo esc_url($meetings_url . 'District-219-Council-Meeting-Agenda.pdf'); ?>" class="d219-btn d219-btn-download" target="_blank"><i class="fa-solid fa-file-pdf"></i> Meeting Agenda</a>
            </div>
        </div>
    </section>

    <!-- Interested in Appointed / Volunteer Roles -->
    <section class="d219-interest-section">
        <div class="d219-container">
            <div class="d219-interest-card">
                <h3><i class="fa-solid fa-hand-holding-heart"></i> Interested in Serving District 219?</h3>
                <p>While nominations for elected roles (District Director, Program Quality Director, Club Growth Director, and Division Directors) are closed, there are many <strong>appointed and volunteer leadership roles</strong> available in the new district &mdash; including Area Directors, Finance Manager, Public Relations, Conference Chair, and more.</p>
                <p>Submit the interest form below and your name will be shared with the newly elected District leaders.</p>
                <a href="https://docs.google.com/forms/d/e/1FAIpQLScVoaKQ8Sq8Yp_mTAwsHnahVUAjr9qXdlOV0wvzzdh9f6L-sQ/viewform" class="d219-btn d219-btn-primary" target="_blank" rel="noopener">
                    <i class="fa-solid fa-paper-plane"></i> Quick Interest Form
                </a>
            </div>
        </div>
    </section>

    <!-- DLC Chair -->
    <section class="d219-dlc-chair-section">
        <div class="d219-container">
            <h2>District Leadership Committee</h2>
            <div class="d219-dlc-chair-card">
                <div class="d219-chair-photo">
                    <img src="<?php echo esc_url($headshots_url . $dlc['photo']); ?>" alt="<?php echo esc_attr($dlc['name']); ?>">
                </div>
                <div class="d219-dlc-chair-info">
                    <h3><?php echo esc_html($dlc['title']); ?></h3>
                    <p class="d219-chair-name"><?php echo esc_html($dlc['name']); ?></p>
                    <p class="d219-chair-desc"><?php echo esc_html($dlc['desc_full']); ?></p>
                    <div class="d219-chair-links">
                        <a href="mailto:<?php echo antispambot($dlc['email']); ?>" class="d219-chair-link" title="Email"><i class="fa-solid fa-envelope"></i></a>
                        <a href="<?php echo esc_url($dlc['linkedin']); ?>" class="d219-chair-link" target="_blank" rel="noopener" title="LinkedIn"><i class="fa-brands fa-linkedin"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Transition Info CTA -->
    <section class="d219-cta-section">
        <div class="d219-container">
            <h2>About the Transition</h2>
            <p>Learn more about the District 219 merger, timeline, territory maps, and meet the full transition committee.</p>
            <div class="d219-cta-buttons">
                <a href="<?php echo esc_url(d219_page_url('transition')); ?>" class="d219-btn d219-btn-primary">
                    <i class="fa-solid fa-circle-info"></i> Transition Overview
                </a>
            </div>
        </div>
    </section>

    <!-- Resources Footer -->
    <section class="d219-resources-footer">
        <div class="d219-container">
            <p class="d219-resources-label">Toastmasters International Resources</p>
            <div class="d219-resources-links">
                <a href="https://www.toastmasters.org/resources/candidate-application" target="_blank" rel="noopener">Candidate Application</a>
                <a href="https://www.toastmasters.org/resources/district-leader-nominating-form" target="_blank" rel="noopener">Nominating Form</a>
                <a href="https://www.toastmasters.org/membership/leadership/districtrealignment" target="_blank" rel="noopener">About Realignment</a>
                <a href="<?php echo esc_url(d219_page_url('transition')); ?>" rel="noopener">Transition Overview</a>
            </div>
            <p class="d219-disclaimer">The information on this website is for the sole use of Toastmasters' members, for Toastmasters business only. It is not to be used for solicitation and distribution of non&#8209;Toastmasters material or information.</p>
        </div>
    </section>

    <!-- Floating Bottom Nav (mobile) -->
    <nav class="d219-floating-nav d219-fnav-dlc" id="d219-floating-nav" aria-label="Quick navigation">
        <a href="<?php echo esc_url(d219_page_url('transition')); ?>" class="d219-fnav-item">
            <i class="fa-solid fa-circle-info"></i>
            <span>D219</span>
        </a>
        <a href="#views" class="d219-fnav-item d219-fnav-active">
            <i class="fa-solid fa-user"></i>
            <span>Profiles</span>
        </a>
        <a href="#" class="d219-fnav-item" id="fnav-compare-btn">
            <i class="fa-solid fa-table-columns"></i>
            <span>Compare</span>
        </a>
        <a href="#" class="d219-fnav-item" data-section="top">
            <i class="fa-solid fa-check-to-slot"></i>
            <span>Election</span>
        </a>
    </nav>

</div>

<script>
(function() {
    var candidates = <?php echo wp_json_encode($candidates_json); ?>;
    var questions = <?php echo wp_json_encode($questions); ?>;

    // === VIEW SWITCHING ===
    var tabs = document.querySelectorAll('.d219-ptab');
    var views = document.querySelectorAll('.d219-view');
    function switchView(viewName) {
        tabs.forEach(function(t) { t.classList.remove('d219-ptab-active'); });
        views.forEach(function(v) { v.classList.remove('d219-view-active'); });
        document.querySelector('[data-view="' + viewName + '"]').classList.add('d219-ptab-active');
        document.getElementById('view-' + viewName).classList.add('d219-view-active');
        // Hide compare sticky header when not on compare view
        if (stickyClone) stickyClone.style.display = (viewName === 'compare') ? '' : 'none';
    }
    tabs.forEach(function(tab) {
        tab.addEventListener('click', function() { switchView(this.dataset.view); });
    });

    // Mobile nav compare button
    var fnavCompare = document.getElementById('fnav-compare-btn');
    if (fnavCompare) {
        fnavCompare.addEventListener('click', function(e) {
            e.preventDefault();
            switchView('compare');
            document.getElementById('views').scrollIntoView({ behavior: 'smooth' });
        });
    }

    // === ROLE FILTER ===
    var filters = document.querySelectorAll('.d219-pfilt');
    filters.forEach(function(f) {
        f.addEventListener('click', function() {
            filters.forEach(function(b) { b.classList.remove('d219-pfilt-active'); });
            this.classList.add('d219-pfilt-active');
            var filter = this.dataset.filter;
            applyFilter(filter);
        });
    });

    function matchesFilter(filter, type, roleSlugsStr) {
        if (filter === 'all') return true;
        if (filter === 'division') return type === 'division';
        var slugs = roleSlugsStr ? roleSlugsStr.split(' ') : [];
        return slugs.indexOf(filter) >= 0;
    }

    function applyFilter(filter) {
        // Browse view — role groups
        document.querySelectorAll('.d219-role-browse-group').forEach(function(group) {
            group.style.display = matchesFilter(filter, group.dataset.type, group.dataset.roleSlug) ? '' : 'none';
        });
        // Compare view — chips: show/hide AND auto-select matching candidates
        var matchCount = 0;
        document.querySelectorAll('.d219-compare-chip').forEach(function(chip) {
            var matches = matchesFilter(filter, chip.dataset.type, chip.dataset.roleSlugs);
            chip.style.display = matches ? '' : 'none';
            if (matches) matchCount++;
        });
        // If a specific filter (not "all"), auto-check matching and uncheck others (up to max 3)
        if (filter !== 'all') {
            var checked = 0;
            document.querySelectorAll('.d219-compare-chip').forEach(function(chip) {
                var cb = chip.querySelector('.d219-compare-cb');
                var matches = matchesFilter(filter, chip.dataset.type, chip.dataset.roleSlugs);
                if (matches && checked < maxCompare) {
                    cb.checked = true;
                    checked++;
                } else {
                    cb.checked = false;
                }
            });
            buildCompareTable();
        } else {
            // "All" filter — keep existing selections so user can add more from full list
            buildCompareTable();
        }
        // Re-run by-question view
        setTimeout(buildQuestionView, 50);
    }

    // === BROWSE: EXPAND/COLLAPSE ===
    document.querySelectorAll('.d219-btn-expand').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var slug = this.dataset.slug;
            var qa = document.querySelector('.d219-profile-qa[data-slug="' + slug + '"]');
            var video = document.querySelector('.d219-profile-video[data-slug="' + slug + '"]');
            if (!qa) return;
            var card = this.closest('.d219-profile-card');
            var isOpen = qa.classList.contains('d219-qa-open');
            qa.classList.toggle('d219-qa-open');
            if (card) card.classList.toggle('d219-card-expanded');
            if (video) video.classList.toggle('d219-video-open');
            this.innerHTML = isOpen
                ? '<i class="fa-solid fa-chevron-down"></i> View Responses'
                : '<i class="fa-solid fa-chevron-up"></i> Hide Responses';
        });
    });

    // === BROWSE: VIDEO TOGGLE (independent of Q&A) ===
    document.querySelectorAll('.d219-btn-video').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var slug = this.dataset.slug;
            var video = document.querySelector('.d219-profile-video[data-slug="' + slug + '"]');
            if (!video) return;
            video.classList.toggle('d219-video-open');
            var isOpen = video.classList.contains('d219-video-open');
            this.innerHTML = isOpen
                ? '<i class="fa-solid fa-video"></i> Hide Video'
                : '<i class="fa-solid fa-video"></i> Video';
        });
    });

    // === COMPARE: CHECKBOX LOGIC ===
    var maxCompare = 3;
    document.querySelectorAll('.d219-compare-cb').forEach(function(cb) {
        cb.addEventListener('change', function() {
            var checked = document.querySelectorAll('.d219-compare-cb:checked');
            if (checked.length > maxCompare) {
                this.checked = false;
                return;
            }
            buildCompareTable();
        });
    });

    function buildCompareTable() {
        var checked = document.querySelectorAll('.d219-compare-cb:checked');
        var wrap = document.getElementById('compare-table-wrap');
        if (checked.length < 1) {
            wrap.innerHTML = '<p class="d219-compare-empty">Select candidates above to view and compare responses.</p>';
            return;
        }
        var slugs = [];
        checked.forEach(function(cb) { slugs.push(cb.value); });
        var selected = slugs.map(function(s) {
            return candidates.find(function(c) { return c.slug === s; });
        });
        var colCount = selected.length;

        var html = '<div class="d219-compare-table d219-compare-cols-' + colCount + '"><table>';
        // Header row
        html += '<thead><tr><th class="d219-ct-label"></th>';
        selected.forEach(function(c) {
            html += '<th class="d219-ct-head">';
            html += '<img src="' + c.photo + '" alt="">';
            html += '<span>' + escHtml(c.name) + '</span>';
            html += '<small>Candidate for ' + escHtml(c.role) + '</small>';
            if (c.bio_pdf) html += '<a href="' + c.bio_pdf + '" target="_blank" rel="noopener" class="d219-ct-pdf" title="Download PDF"><i class="fa-solid fa-file-pdf"></i></a>';
            html += '</th>';
        });
        html += '</tr></thead><tbody>';

        // Video row
        html += '<tr><td class="d219-ct-label"><i class="fa-solid fa-video"></i> Showcase Video</td>';
        selected.forEach(function(c) {
            if (c.video) {
                html += '<td class="d219-ct-video"><div class="d219-video-embed-sm">' + c.video + '</div></td>';
            } else {
                html += '<td class="d219-ct-video-soon">Available after April 22, 2026</td>';
            }
        });
        html += '</tr>';

        // Question rows (skip showcase_video — already rendered above)
        for (var key in questions) {
            if (key === 'showcase_video') continue;
            html += '<tr><td class="d219-ct-label">' + escHtml(questions[key]) + '</td>';
            selected.forEach(function(c) {
                var ans = (c.answers && c.answers[key]) ? c.answers[key] : '';
                if (!ans) {
                    html += '<td class="d219-ct-empty">No response provided</td>';
                } else {
                    html += '<td>' + escHtml(ans) + '</td>';
                }
            });
            html += '</tr>';
        }
        html += '</tbody></table></div>';
        wrap.innerHTML = html;
        setupCompareSticky();
    }

    // === COMPARE: STICKY HEADER via JS (CSS sticky doesn't work inside overflow-x container) ===
    var stickyClone = null;
    var stickyScrollHandler = null;
    var stickyWrapHandler = null;
    var stickyResizeHandler = null;

    function setupCompareSticky() {
        // Clean up previous listeners & clone
        if (stickyScrollHandler) window.removeEventListener('scroll', stickyScrollHandler);
        if (stickyResizeHandler) window.removeEventListener('resize', stickyResizeHandler);
        var oldWrap = document.getElementById('compare-table-wrap');
        if (stickyWrapHandler && oldWrap) oldWrap.removeEventListener('scroll', stickyWrapHandler);
        if (stickyClone && stickyClone.parentNode) stickyClone.parentNode.removeChild(stickyClone);
        stickyClone = null;

        var table = document.querySelector('.d219-compare-table table');
        if (!table) return;
        var thead = table.querySelector('thead');
        if (!thead) return;
        var wrap = document.getElementById('compare-table-wrap');

        // Create floating clone of header — appended to body so it's outside overflow container
        var clone = document.createElement('div');
        clone.className = 'd219-compare-sticky-header';
        clone.style.display = 'none';
        clone.innerHTML = '<table>' + thead.outerHTML + '</table>';
        document.body.appendChild(clone);
        stickyClone = clone;

        function syncWidths() {
            var origThs = thead.querySelectorAll('th');
            var cloneThs = clone.querySelectorAll('th');
            clone.querySelector('table').style.width = table.offsetWidth + 'px';
            origThs.forEach(function(th, i) {
                if (cloneThs[i]) cloneThs[i].style.width = th.offsetWidth + 'px';
            });
        }

        function positionClone() {
            if (!stickyClone) return;
            var nav = document.querySelector('.d219-profiles-nav-section');
            var navH = nav ? nav.offsetHeight : 0;
            var theadRect = thead.getBoundingClientRect();
            var tableRect = table.getBoundingClientRect();
            var theadH = thead.offsetHeight;
            var wrapRect = wrap.getBoundingClientRect();

            if (theadRect.top < navH && tableRect.bottom > navH + theadH + 20) {
                syncWidths();
                stickyClone.style.display = 'block';
                stickyClone.style.top = navH + 'px';
                stickyClone.style.left = wrapRect.left + 'px';
                stickyClone.style.width = wrapRect.width + 'px';
                // Sync horizontal scroll position
                clone.scrollLeft = wrap.scrollLeft;
            } else {
                stickyClone.style.display = 'none';
            }
        }

        stickyScrollHandler = function() { positionClone(); };
        stickyWrapHandler = function() {
            if (stickyClone && stickyClone.style.display !== 'none') {
                stickyClone.scrollLeft = wrap.scrollLeft;
            }
        };
        stickyResizeHandler = function() { positionClone(); };

        window.addEventListener('scroll', stickyScrollHandler, { passive: true });
        wrap.addEventListener('scroll', stickyWrapHandler, { passive: true });
        window.addEventListener('resize', stickyResizeHandler, { passive: true });
    }

    // === BY QUESTION VIEW ===
    var qSelect = document.getElementById('d219-q-select');
    qSelect.addEventListener('change', buildQuestionView);
    buildQuestionView();

    function buildQuestionView() {
        var key = qSelect.value;
        var wrap = document.getElementById('question-results');
        var activeFilter = document.querySelector('.d219-pfilt-active');
        var filter = activeFilter ? activeFilter.dataset.filter : 'all';
        var isVideo = (key === 'showcase_video');
        var html = '';
        candidates.forEach(function(c) {
            // Apply role filter
            if (!matchesFilter(filter, c.type, c.all_role_slugs.join(' '))) return;
            if (isVideo) {
                // Show video embed or coming soon
                html += '<div class="d219-qr-card">';
                html += '<div class="d219-qr-head">';
                html += '<img src="' + c.photo + '" alt="">';
                html += '<div class="d219-qr-info"><strong>' + escHtml(c.name) + '</strong><span>Candidate for ' + escHtml(c.role) + '</span></div>';
                html += '<div class="d219-qr-links">';
                if (c.bio_pdf) html += '<a href="' + c.bio_pdf + '" target="_blank" rel="noopener" title="Download PDF"><i class="fa-solid fa-file-pdf"></i></a>';
                html += '</div>';
                html += '</div>';
                if (c.video) {
                    html += '<div class="d219-video-embed">' + c.video + '</div>';
                } else {
                    html += '<div class="d219-video-placeholder"><i class="fa-solid fa-video"></i><span>Candidate Showcase Video — Available after April 22, 2026</span></div>';
                }
                html += '</div>';
            } else {
                var ans = (c.answers && c.answers[key]) ? c.answers[key] : '';
                if (!ans) return;
                html += '<div class="d219-qr-card">';
                html += '<div class="d219-qr-head">';
                html += '<img src="' + c.photo + '" alt="">';
                html += '<div class="d219-qr-info"><strong>' + escHtml(c.name) + '</strong><span>Candidate for ' + escHtml(c.role) + '</span></div>';
                html += '<div class="d219-qr-links">';
                if (c.video) html += '<a href="#" class="d219-qr-video-link" data-slug="' + c.slug + '" title="Watch Video"><i class="fa-solid fa-video"></i></a>';
                if (c.bio_pdf) html += '<a href="' + c.bio_pdf + '" target="_blank" rel="noopener" title="Download PDF"><i class="fa-solid fa-file-pdf"></i></a>';
                html += '</div>';
                html += '</div>';
                html += '<p>' + escHtml(ans) + '</p>';
                html += '</div>';
            }
        });
        wrap.innerHTML = html || '<p class="d219-compare-empty">No responses for this question with current filter.</p>';
        // Wire up video links in by-question view
        wrap.querySelectorAll('.d219-qr-video-link').forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                // Switch to video question
                qSelect.value = 'showcase_video';
                buildQuestionView();
            });
        });
    }

    function escHtml(s) {
        if (!s) return '';
        var d = document.createElement('div');
        d.textContent = s;
        return d.innerHTML;
    }

    // === STICKY NAV HEIGHT — sets CSS custom property for sticky elements below nav ===
    function updateNavHeight() {
        var nav = document.querySelector('.d219-profiles-nav-section');
        if (nav) document.documentElement.style.setProperty('--nav-h', nav.offsetHeight + 'px');
    }
    updateNavHeight();
    window.addEventListener('resize', updateNavHeight);

    // === FLOATING NAV ===
    var floatingNav = document.getElementById('d219-floating-nav');
    if (floatingNav) {
        function checkNavVisibility() {
            floatingNav.classList.toggle('d219-fnav-visible', window.scrollY > 200);
        }
        window.addEventListener('scroll', checkNavVisibility);
        checkNavVisibility();
        floatingNav.querySelectorAll('.d219-fnav-item').forEach(function(item) {
            item.addEventListener('click', function(e) {
                var href = this.getAttribute('href');
                if (this.id === 'fnav-compare-btn') return;
                if (href === '#') {
                    e.preventDefault();
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                } else if (href.charAt(0) === '#') {
                    e.preventDefault();
                    var target = document.querySelector(href);
                    if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    }
})();
</script>

<?php get_footer(); ?>
