<?php
/**
 * Template for District 219 DLC (District Leadership Committee) Page
 * Handles nominations and leadership applications
 */

if (!defined('ABSPATH')) exit;

$headshots_url = D219_ASSETS_URL . 'headshots/';
$committees = d219_get_committees();
$nomination_forms = d219_get_nomination_forms();
$dlc = $committees['leadership'];

get_header();
?>
<div class="d219-transition-page d219-dlc-page d219-page">
    
    <!-- Hero -->
    <section class="d219-hero d219-hero-dlc">
        <div class="d219-container">
            <h1 class="d219-title">District 219 Leadership</h1>
            <p class="d219-subtitle">Call for Nominations — 2026–2027 Toastmasters Year</p>
            <div class="d219-hero-nominations">
                <p><i class="fa-solid fa-clock"></i> <strong>Nomination Deadline:</strong> February 25, 2026</p>
            </div>
        </div>
    </section>
    
    <!-- Call for Nominations -->
    <section class="d219-nominations-section" id="nominations">
        <div class="d219-container">
            
            <div class="d219-nominations-grid">
                <div class="d219-nominations-main">
                    <div class="d219-nominations-intro-card">
                        <p>District 219 is excited to announce the open call for nominations and applications for the 2026-2027 Toastmasters leadership year! As a newly forming district created from <a href="https://district10.org/" target="_blank" rel="noopener">District 10</a> and <a href="https://d13tm.com/" target="_blank" rel="noopener">District 13</a>, and welcoming <a href="https://www.toastmasters.org/Find-a-Club/00002960-greater-williamsport-club" target="_blank" rel="noopener">Greater Williamsport Toastmasters</a> from <a href="https://tmdistrict38.org/" target="_blank" rel="noopener">District 38</a>, we have a unique and historic opportunity to build strong leadership that will guide our district into a bright and successful future.</p>
                        <p>Leadership in Toastmasters is both a privilege and a powerful growth experience — a chance to serve your fellow members, strengthen your leadership skills, and contribute to the success of clubs and members across our district. Now is your chance to step forward, lead with purpose, and help shape the culture and achievements of District 219.</p>
                        <p>Whether you're an experienced Toastmaster ready for a new challenge or someone who has demonstrated great potential in your club, we encourage you — or someone you know — to consider applying or submitting a nomination.</p>
                        <p class="d219-tagline"><strong>Step up. Lead forward. Shape the future.</strong></p>
                    </div>
                    
                    <div class="d219-positions-card">
                        <h3><i class="fa-solid fa-users-gear"></i> Positions Available</h3>
                        <div class="d219-positions-columns">
                            <div>
                                <h4>Elected</h4>
                                <ul>
                                    <li>District Director</li>
                                    <li>Program Quality Director</li>
                                    <li>Club Growth Director</li>
                                    <li>Division Directors</li>
                                </ul>
                            </div>
                            <div>
                                <h4>Appointed</h4>
                                <ul>
                                    <li>Area Directors</li>
                                    <li>Public Relations Manager</li>
                                    <li>Finance Manager</li>
                                    <li>Administration Manager</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d219-how-apply-card">
                        <h3><i class="fa-solid fa-file-signature"></i> How to Apply</h3>
                        <div class="d219-apply-steps">
                            <div class="d219-apply-step">
                                <span class="d219-step-num">1</span>
                                <div>
                                    <strong>All Nominees:</strong> Complete the <a href="<?php echo esc_url(D219_ASSETS_URL . 'forms/450c-district-leader-nominating-form.pdf'); ?>" download>District Leader Nominating Form</a>
                                </div>
                            </div>
                            <div class="d219-apply-step">
                                <span class="d219-step-num">2</span>
                                <div>
                                    <strong>Elected Positions:</strong> Also complete the <a href="<?php echo esc_url(D219_ASSETS_URL . 'forms/450e-candidate-application.pdf'); ?>" download>Candidate Application</a>, <a href="<?php echo esc_url(D219_ASSETS_URL . 'forms/450h-district-officer-biographical-info.pdf'); ?>" download>Biographical Info</a>, and <a href="<?php echo esc_url(D219_ASSETS_URL . 'forms/450D-district-leader-agreement-statement-ff.pdf'); ?>" download>Agreement Statement</a>
                                </div>
                            </div>
                            <div class="d219-apply-step">
                                <span class="d219-step-num">3</span>
                                <div>
                                    <strong>Submit:</strong> Email completed forms to <a href="mailto:district219dlc1@gmail.com">district219dlc1@gmail.com</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="d219-nominations-sidebar">
                    <div class="d219-deadline-card">
                        <div class="d219-deadline-badge">Deadline</div>
                        <div class="d219-deadline-date">February 25, 2026</div>
                        <p>Submit nominations for District 219 leadership</p>
                    </div>
                    
                    <?php if (!empty($nomination_forms)) : ?>
                    <div class="d219-forms-card">
                        <h4><i class="fa-solid fa-file-pdf"></i> Nomination Forms</h4>
                        <div class="d219-forms-list">
                            <?php foreach ($nomination_forms as $form) : ?>
                            <a href="<?php echo esc_url($form['url']); ?>" class="d219-form-link" download>
                                <span class="d219-form-icon">
                                    <i class="fa-regular fa-file-pdf d219-icon-pdf"></i>
                                    <i class="fa-solid fa-download d219-icon-download"></i>
                                </span>
                                <?php echo esc_html(str_replace(array('(450C)', '(450E)', '(450H)', '(450D)'), '', $form['title'])); ?>
                            </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="d219-why-card">
                        <h4><i class="fa-solid fa-star"></i> Why Serve?</h4>
                        <ul>
                            <li>Develop leadership &amp; communication skills</li>
                            <li>Support clubs in achieving goals</li>
                            <li>Foster member growth &amp; excellence</li>
                            <li>Connect with Toastmasters across 4 states</li>
                        </ul>
                    </div>
                    
                    <a href="https://docs.google.com/forms/d/e/1FAIpQLScVoaKQ8Sq8Yp_mTAwsHnahVUAjr9qXdlOV0wvzzdh9f6L-sQ/viewform" class="d219-btn d219-btn-interest" target="_blank" rel="noopener">
                        <i class="fa-solid fa-paper-plane"></i> Quick Interest Form
                    </a>
                    
                    <a href="mailto:district219dlc1@gmail.com" class="d219-btn d219-btn-contact">
                        <i class="fa-solid fa-envelope"></i> Questions? Contact Us
                    </a>
                </div>
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
                        <a href="mailto:<?php echo esc_attr($dlc['email']); ?>" class="d219-chair-link" title="Email"><i class="fa-solid fa-envelope"></i></a>
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
        <a href="/transition#alignment" class="d219-fnav-item">
            <i class="fa-solid fa-map"></i>
            <span>Alignment</span>
        </a>
        <a href="/transition#clubs" class="d219-fnav-item">
            <i class="fa-solid fa-address-book"></i>
            <span>Clubs</span>
        </a>
        <a href="#" class="d219-fnav-item d219-fnav-active" data-section="top">
            <i class="fa-solid fa-hand"></i>
            <span>Nominate</span>
        </a>
    </nav>

</div>

<script>
(function() {
    var floatingNav = document.getElementById('d219-floating-nav');
    if (!floatingNav) return;

    // Show nav after scrolling a bit
    function checkNavVisibility() {
        if (window.scrollY > 200) {
            floatingNav.classList.add('d219-fnav-visible');
        } else {
            floatingNav.classList.remove('d219-fnav-visible');
        }
    }
    window.addEventListener('scroll', checkNavVisibility);
    checkNavVisibility();

    // Smooth scroll for same-page anchor (#)
    floatingNav.querySelectorAll('.d219-fnav-item').forEach(function(item) {
        item.addEventListener('click', function(e) {
            var href = this.getAttribute('href');
            if (href === '#') {
                e.preventDefault();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });
    });
})();
</script>

<?php get_footer(); ?>
