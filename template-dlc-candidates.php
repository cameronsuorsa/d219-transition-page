<?php
/**
 * Template for District 219 DLC — Nominated Candidates Showcase
 * Shows the nominated slate for the 2026-2027 Toastmasters year
 */

if (!defined('ABSPATH')) exit;

$candidates_url = D219_ASSETS_URL . 'candidates/';
$roles = d219_get_candidates();
$committees = d219_get_committees();
$dlc = $committees['leadership'];
$headshots_url = D219_ASSETS_URL . 'headshots/';

get_header();
?>
<div class="d219-transition-page d219-dlc-page d219-page">

    <!-- Hero -->
    <section class="d219-hero d219-hero-dlc">
        <div class="d219-container">
            <h1 class="d219-title">District 219 Candidates</h1>
            <p class="d219-subtitle">Nominations are closed — the candidate slate has been announced</p>
            <div class="d219-hero-nominations">
                <p><i class="fa-solid fa-calendar-check"></i> <strong>Election Meeting:</strong> April 27, 2026 &middot; 7:00 PM via Zoom</p>
            </div>
        </div>
    </section>

    <!-- Intro -->
    <section class="d219-candidates-intro-section">
        <div class="d219-container">
            <div class="d219-candidates-intro-card">
                <p>The District Leadership Committee for District 219 is pleased to announce the nominated candidates for District Office for the <strong>2026–2027 Toastmasters year</strong>. These individuals have stepped forward to lead our newly forming district, created from <a href="https://district10.org/" target="_blank" rel="noopener">District 10</a> and <a href="https://d13tm.com/" target="_blank" rel="noopener">District 13</a>.</p>
                <p>Elections will be held at the <strong>District 219 Business Meeting on April 27, 2026</strong>. Candidate Showcase videos will be available after April 22nd.</p>
                <p><em>Candidates are listed in alphabetical order by last name.</em></p>
            </div>
        </div>
    </section>

    <!-- Elected Officers -->
    <section class="d219-candidates-section" id="elected">
        <div class="d219-container">
            <h2><i class="fa-solid fa-award"></i> Elected Officer Candidates</h2>

            <?php foreach ($roles as $role) : if ($role['type'] !== 'elected') continue; ?>
            <div class="d219-role-group">
                <h3 class="d219-role-title"><?php echo esc_html($role['role']); ?></h3>
                <div class="d219-candidates-grid d219-candidates-<?php echo count($role['candidates']); ?>">
                    <?php foreach ($role['candidates'] as $c) : ?>
                    <div class="d219-candidate-card">
                        <div class="d219-candidate-photo">
                            <img src="<?php echo esc_url($candidates_url . 'photos/' . $c['photo']); ?>" alt="<?php echo esc_attr(explode(',', $c['name'])[0]); ?>">
                        </div>
                        <div class="d219-candidate-info">
                            <h4><?php echo esc_html(explode(',', $c['name'])[0]); ?></h4>
                            <p class="d219-candidate-credentials"><?php echo esc_html(trim(substr($c['name'], strpos($c['name'], ',') + 1))); ?></p>
                            <?php if ($c['bio']) : ?>
                            <a href="<?php echo esc_url($candidates_url . 'bios/' . $c['bio']); ?>" class="d219-candidate-bio-link" target="_blank" rel="noopener">
                                <i class="fa-solid fa-file-pdf"></i> View Bio
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Division Directors -->
    <section class="d219-candidates-section d219-candidates-divisions" id="divisions">
        <div class="d219-container">
            <h2><i class="fa-solid fa-sitemap"></i> Division Director Candidates</h2>

            <?php foreach ($roles as $role) : if ($role['type'] !== 'division') continue; ?>
            <div class="d219-role-group">
                <h3 class="d219-role-title"><?php echo esc_html($role['role']); ?><?php if (!empty($role['region'])) : ?> <span class="d219-role-region"><?php echo esc_html($role['region']); ?></span><?php endif; ?></h3>
                <div class="d219-candidates-grid d219-candidates-<?php echo count($role['candidates']); ?>">
                    <?php foreach ($role['candidates'] as $c) : ?>
                    <div class="d219-candidate-card">
                        <div class="d219-candidate-photo">
                            <img src="<?php echo esc_url($candidates_url . 'photos/' . $c['photo']); ?>" alt="<?php echo esc_attr(explode(',', $c['name'])[0]); ?>">
                        </div>
                        <div class="d219-candidate-info">
                            <h4><?php echo esc_html(explode(',', $c['name'])[0]); ?></h4>
                            <p class="d219-candidate-credentials"><?php echo esc_html(trim(substr($c['name'], strpos($c['name'], ',') + 1))); ?></p>
                            <?php if ($c['bio']) : ?>
                            <a href="<?php echo esc_url($candidates_url . 'bios/' . $c['bio']); ?>" class="d219-candidate-bio-link" target="_blank" rel="noopener">
                                <i class="fa-solid fa-file-pdf"></i> View Bio
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Election Info -->
    <section class="d219-candidates-election-section">
        <div class="d219-container">
            <div class="d219-election-card">
                <div class="d219-election-icon"><i class="fa-solid fa-check-to-slot"></i></div>
                <div class="d219-election-details">
                    <h3>District 219 Election Meeting</h3>
                    <p class="d219-election-date">April 27, 2026 &middot; 7:00 PM via Zoom</p>
                    <p>All club members in good standing are eligible to vote. Each chartered club may send delegates to cast votes.</p>
                </div>
                <?php $zoom_link = defined('D219_ZOOM_LINK') ? D219_ZOOM_LINK : ''; ?>
                <?php if (!empty($zoom_link)) : ?>
                <a href="<?php echo esc_url($zoom_link); ?>" class="d219-btn d219-btn-primary" target="_blank" rel="noopener">
                    <i class="fa-solid fa-video"></i> Join on Zoom
                </a>
                <?php endif; ?>
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
                <a href="/transition" class="d219-btn d219-btn-primary">
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
                <a href="/transition" rel="noopener">Transition Overview</a>
            </div>
            <p class="d219-disclaimer">The information on this website is for the sole use of Toastmasters' members, for Toastmasters business only. It is not to be used for solicitation and distribution of non&#8209;Toastmasters material or information.</p>
        </div>
    </section>

    <!-- Floating Bottom Nav (mobile) -->
    <nav class="d219-floating-nav d219-fnav-dlc" id="d219-floating-nav" aria-label="Quick navigation">
        <a href="/transition" class="d219-fnav-item">
            <i class="fa-solid fa-circle-info"></i>
            <span>D219</span>
        </a>
        <a href="#elected" class="d219-fnav-item">
            <i class="fa-solid fa-award"></i>
            <span>Officers</span>
        </a>
        <a href="#divisions" class="d219-fnav-item">
            <i class="fa-solid fa-sitemap"></i>
            <span>Divisions</span>
        </a>
        <a href="#" class="d219-fnav-item d219-fnav-active" data-section="top">
            <i class="fa-solid fa-check-to-slot"></i>
            <span>Election</span>
        </a>
    </nav>

</div>

<script>
(function() {
    var floatingNav = document.getElementById('d219-floating-nav');
    if (!floatingNav) return;

    function checkNavVisibility() {
        if (window.scrollY > 200) {
            floatingNav.classList.add('d219-fnav-visible');
        } else {
            floatingNav.classList.remove('d219-fnav-visible');
        }
    }
    window.addEventListener('scroll', checkNavVisibility);
    checkNavVisibility();

    floatingNav.querySelectorAll('.d219-fnav-item').forEach(function(item) {
        item.addEventListener('click', function(e) {
            var href = this.getAttribute('href');
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
})();
</script>

<?php get_footer(); ?>
