<?php
/**
 * Template for District 219 Candidate Profiles — Browse, Compare & Video
 * Interactive tool for exploring candidate Q&A without downloading PDFs
 */

if (!defined('ABSPATH')) exit;

$candidates_url = D219_ASSETS_URL . 'candidates/';
$bios = d219_get_candidate_bios();
$roles = d219_get_candidates();

// Build question labels
$questions = array(
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
    );
}

get_header();
?>
<div class="d219-transition-page d219-profiles-page d219-page">

    <!-- Hero -->
    <section class="d219-hero d219-hero-profiles">
        <div class="d219-container">
            <h1 class="d219-title">Candidate Profiles</h1>
            <p class="d219-subtitle">Explore candidates, compare responses, and watch showcase videos</p>
            <p class="d219-hero-note"><em>Candidates listed in alphabetical order by last name</em></p>
        </div>
    </section>

    <!-- View Tabs -->
    <section class="d219-profiles-nav-section">
        <div class="d219-container">
            <div class="d219-profiles-tabs">
                <button class="d219-ptab d219-ptab-active" data-view="browse"><i class="fa-solid fa-user"></i> <span>Browse</span></button>
                <button class="d219-ptab" data-view="compare"><i class="fa-solid fa-columns"></i> <span>Compare</span></button>
                <button class="d219-ptab" data-view="question"><i class="fa-solid fa-list-check"></i> <span>By Question</span></button>
            </div>
            <!-- Role filter -->
            <div class="d219-profiles-filter">
                <button class="d219-pfilt d219-pfilt-active" data-filter="all">All</button>
                <button class="d219-pfilt" data-filter="elected">Elected Officers</button>
                <button class="d219-pfilt" data-filter="division">Division Directors</button>
            </div>
        </div>
    </section>

    <!-- ============ BROWSE VIEW ============ -->
    <section class="d219-view d219-view-browse d219-view-active" id="view-browse">
        <div class="d219-container">
            <div class="d219-browse-grid">
                <?php foreach ($bios as $slug => $bio) : if (empty($bio['answers'])) continue; ?>
                <div class="d219-profile-card" data-slug="<?php echo esc_attr($slug); ?>" data-type="<?php echo esc_attr($bio['type']); ?>">
                    <div class="d219-profile-card-top">
                        <img src="<?php echo esc_url($candidates_url . 'photos/' . $bio['photo']); ?>" alt="<?php echo esc_attr($bio['name']); ?>">
                        <div class="d219-profile-card-info">
                            <h3><?php echo esc_html($bio['name']); ?></h3>
                            <p class="d219-profile-creds"><?php echo esc_html($bio['credentials']); ?></p>
                            <p class="d219-profile-role"><?php echo esc_html($bio['role']); ?><?php if ($bio['region']) echo ' <span class="d219-profile-region">(' . esc_html($bio['region']) . ')</span>'; ?></p>
                        </div>
                    </div>
                    <div class="d219-profile-actions">
                        <button class="d219-btn-sm d219-btn-expand" data-slug="<?php echo esc_attr($slug); ?>"><i class="fa-solid fa-chevron-down"></i> View Responses</button>
                        <?php if ($bio['bio_pdf']) : ?>
                        <a href="<?php echo esc_url($candidates_url . 'bios/' . $bio['bio_pdf']); ?>" class="d219-btn-sm d219-btn-pdf" target="_blank" rel="noopener"><i class="fa-solid fa-file-pdf"></i> PDF</a>
                        <?php endif; ?>
                    </div>
                    <!-- Video -->
                    <div class="d219-profile-video" data-slug="<?php echo esc_attr($slug); ?>">
                        <div class="d219-video-placeholder">
                            <i class="fa-solid fa-video"></i>
                            <span>Candidate Showcase Video — Coming Soon</span>
                        </div>
                    </div>
                    <!-- Expandable Q&A -->
                    <div class="d219-profile-qa" data-slug="<?php echo esc_attr($slug); ?>">
                        <?php foreach ($questions as $key => $label) :
                            $answer = isset($bio['answers'][$key]) ? $bio['answers'][$key] : '';
                            if (empty($answer)) continue;
                        ?>
                        <div class="d219-qa-item">
                            <h4><?php echo esc_html($label); ?></h4>
                            <p><?php echo esc_html($answer); ?></p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- ============ COMPARE VIEW ============ -->
    <section class="d219-view d219-view-compare" id="view-compare">
        <div class="d219-container">
            <p class="d219-compare-hint">Select 2 or 3 candidates to compare side by side.</p>
            <div class="d219-compare-picker">
                <?php foreach ($bios as $slug => $bio) : if (empty($bio['answers'])) continue; ?>
                <label class="d219-compare-chip" data-type="<?php echo esc_attr($bio['type']); ?>">
                    <input type="checkbox" value="<?php echo esc_attr($slug); ?>" class="d219-compare-cb">
                    <img src="<?php echo esc_url($candidates_url . 'photos/' . $bio['photo']); ?>" alt="">
                    <span><?php echo esc_html($bio['name']); ?></span>
                    <small><?php echo esc_html($bio['role']); ?></small>
                </label>
                <?php endforeach; ?>
            </div>
            <div class="d219-compare-table-wrap" id="compare-table-wrap">
                <!-- JS fills this -->
            </div>
        </div>
    </section>

    <!-- ============ BY QUESTION VIEW ============ -->
    <section class="d219-view d219-view-question" id="view-question">
        <div class="d219-container">
            <div class="d219-question-picker">
                <label for="d219-q-select">Choose a question:</label>
                <select id="d219-q-select">
                    <?php foreach ($questions as $key => $label) : ?>
                    <option value="<?php echo esc_attr($key); ?>"><?php echo esc_html($label); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="d219-question-results" id="question-results">
                <!-- JS fills this -->
            </div>
        </div>
    </section>

    <!-- Back to DLC -->
    <section class="d219-cta-section">
        <div class="d219-container">
            <div class="d219-cta-buttons">
                <a href="<?php echo esc_url(d219_page_url('dlc')); ?>" class="d219-btn d219-btn-primary"><i class="fa-solid fa-arrow-left"></i> Back to Candidates</a>
                <a href="<?php echo esc_url(d219_page_url('transition')); ?>" class="d219-btn d219-btn-secondary"><i class="fa-solid fa-circle-info"></i> Transition Overview</a>
            </div>
        </div>
    </section>

    <!-- Resources Footer -->
    <section class="d219-resources-footer">
        <div class="d219-container">
            <p class="d219-disclaimer">The information on this website is for the sole use of Toastmasters' members, for Toastmasters business only. It is not to be used for solicitation and distribution of non&#8209;Toastmasters material or information.</p>
        </div>
    </section>

</div>

<script>
(function() {
    var candidates = <?php echo wp_json_encode($candidates_json); ?>;
    var questions = <?php echo wp_json_encode($questions); ?>;

    // === VIEW SWITCHING ===
    var tabs = document.querySelectorAll('.d219-ptab');
    var views = document.querySelectorAll('.d219-view');
    tabs.forEach(function(tab) {
        tab.addEventListener('click', function() {
            tabs.forEach(function(t) { t.classList.remove('d219-ptab-active'); });
            views.forEach(function(v) { v.classList.remove('d219-view-active'); });
            this.classList.add('d219-ptab-active');
            document.getElementById('view-' + this.dataset.view).classList.add('d219-view-active');
        });
    });

    // === ROLE FILTER ===
    var filters = document.querySelectorAll('.d219-pfilt');
    filters.forEach(function(f) {
        f.addEventListener('click', function() {
            filters.forEach(function(b) { b.classList.remove('d219-pfilt-active'); });
            this.classList.add('d219-pfilt-active');
            var filter = this.dataset.filter;
            // Browse view
            document.querySelectorAll('.d219-profile-card').forEach(function(card) {
                card.style.display = (filter === 'all' || card.dataset.type === filter) ? '' : 'none';
            });
            // Compare view chips
            document.querySelectorAll('.d219-compare-chip').forEach(function(chip) {
                chip.style.display = (filter === 'all' || chip.dataset.type === filter) ? '' : 'none';
            });
        });
    });

    // === BROWSE: EXPAND/COLLAPSE ===
    document.querySelectorAll('.d219-btn-expand').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var slug = this.dataset.slug;
            var qa = document.querySelector('.d219-profile-qa[data-slug="' + slug + '"]');
            var video = document.querySelector('.d219-profile-video[data-slug="' + slug + '"]');
            var isOpen = qa.classList.contains('d219-qa-open');
            qa.classList.toggle('d219-qa-open');
            video.classList.toggle('d219-video-open');
            this.innerHTML = isOpen
                ? '<i class="fa-solid fa-chevron-down"></i> View Responses'
                : '<i class="fa-solid fa-chevron-up"></i> Hide Responses';
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
        if (checked.length < 2) {
            wrap.innerHTML = '<p class="d219-compare-hint">Select at least 2 candidates above.</p>';
            return;
        }
        var slugs = [];
        checked.forEach(function(cb) { slugs.push(cb.value); });
        var selected = slugs.map(function(s) {
            return candidates.find(function(c) { return c.slug === s; });
        });

        var html = '<div class="d219-compare-table"><table>';
        // Header row with photos/names
        html += '<thead><tr><th class="d219-ct-label"></th>';
        selected.forEach(function(c) {
            html += '<th class="d219-ct-head"><img src="' + c.photo + '" alt=""><span>' + c.name + '</span><small>' + c.role + '</small></th>';
        });
        html += '</tr></thead><tbody>';
        // Question rows
        for (var key in questions) {
            html += '<tr><td class="d219-ct-label">' + questions[key] + '</td>';
            selected.forEach(function(c) {
                var ans = (c.answers && c.answers[key]) ? c.answers[key] : '—';
                html += '<td>' + escHtml(ans) + '</td>';
            });
            html += '</tr>';
        }
        html += '</tbody></table></div>';
        wrap.innerHTML = html;
    }

    // === BY QUESTION VIEW ===
    var qSelect = document.getElementById('d219-q-select');
    qSelect.addEventListener('change', buildQuestionView);
    buildQuestionView();

    function buildQuestionView() {
        var key = qSelect.value;
        var wrap = document.getElementById('question-results');
        var html = '';
        candidates.forEach(function(c) {
            if (!c.answers || !c.answers[key]) return;
            html += '<div class="d219-qr-card">';
            html += '<div class="d219-qr-head"><img src="' + c.photo + '" alt=""><div><strong>' + escHtml(c.name) + '</strong><span>' + escHtml(c.role) + '</span></div></div>';
            html += '<p>' + escHtml(c.answers[key]) + '</p>';
            html += '</div>';
        });
        wrap.innerHTML = html || '<p>No responses for this question.</p>';
    }

    function escHtml(s) {
        var d = document.createElement('div');
        d.textContent = s;
        return d.innerHTML;
    }
})();
</script>

<?php get_footer(); ?>
