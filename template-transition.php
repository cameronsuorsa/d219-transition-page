<?php
/**
 * Template for District 219 Transition Page
 * Uses WordPress template hierarchy properly for Elementor compatibility
 */

if (!defined('ABSPATH')) exit;

$zoom_link = defined('D219_ZOOM_LINK') ? D219_ZOOM_LINK : '';
$headshots_url = D219_ASSETS_URL . 'headshots/';
$maps_url = D219_ASSETS_URL . 'maps/';
$slides = d219_get_slides();
$has_slides = !empty($slides);
$slides_pdf = d219_get_slides_pdf();
$committees = d219_get_committees();

get_header();
?>
<div class="d219-transition-page d219-page">
    
    <!-- Hero -->
    <section class="d219-hero">
        <div class="d219-container">
            <h1 class="d219-title">District 219 Transition</h1>
            <p class="d219-subtitle">Toastmasters <a href="https://district10.org/" target="_blank" rel="noopener">District 10</a> &amp; <a href="https://d13tm.com/" target="_blank" rel="noopener">District 13</a> are joining together to form District 219</p>
            <div class="d219-hero-nominations">
                <p><i class="fa-solid fa-bullhorn"></i> <strong>Now Accepting Leadership Nominations</strong> — Deadline: February 25, 2026</p>
                <a href="/dlc" class="d219-btn d219-btn-hero"><i class="fa-solid fa-hand"></i> I Want to Help</a>
            </div>
        </div>
    </section>
    
    <!-- What's Happening -->
    <section class="d219-intro-section">
        <div class="d219-container">
            <div class="d219-intro-content">
                <h2>What's Happening?</h2>
                <p>As part of Toastmasters International's worldwide district realignment, <strong><a href="https://district10.org/" target="_blank" rel="noopener">District 10</a> and <a href="https://d13tm.com/" target="_blank" rel="noopener">District 13</a> will merge to form the new District 219</strong> beginning July 1, 2026. This change is part of a larger restructuring effort to better serve members and strengthen clubs across the organization.</p>
                
                <div class="d219-timeline">
                    <div class="d219-timeline-item">
                        <div class="d219-timeline-marker d219-complete"><i class="fa-solid fa-check"></i></div>
                        <div class="d219-timeline-content">
                            <strong>2024</strong>
                            <span>TI Board announces district realignment</span>
                        </div>
                    </div>
                    <div class="d219-timeline-item">
                        <div class="d219-timeline-marker d219-complete"><i class="fa-solid fa-check"></i></div>
                        <div class="d219-timeline-content">
                            <strong>January 2026</strong>
                            <span>Transition committees begin work</span>
                        </div>
                    </div>
                    <div class="d219-timeline-item">
                        <div class="d219-timeline-marker d219-current"><span>●</span></div>
                        <div class="d219-timeline-content">
                            <strong>February 25, 2026</strong>
                            <span>Leadership nominations due</span>
                        </div>
                    </div>
                    <div class="d219-timeline-item">
                        <div class="d219-timeline-marker"><span>●</span></div>
                        <div class="d219-timeline-content">
                            <strong>March 12, 2026</strong>
                            <span>Alignment Town Hall Q&amp;A</span>
                        </div>
                    </div>
                    <div class="d219-timeline-item">
                        <div class="d219-timeline-marker"><span>3</span></div>
                        <div class="d219-timeline-content">
                            <strong>April 27, 2026</strong>
                            <span>District 219 election meeting</span>
                        </div>
                    </div>
                    <div class="d219-timeline-item">
                        <div class="d219-timeline-marker d219-final"><i class="fa-solid fa-flag-checkered"></i></div>
                        <div class="d219-timeline-content">
                            <strong>July 1, 2026</strong>
                            <span>District 219 officially begins!</span>
                        </div>
                    </div>
                </div>
                
                <!-- Nomination CTA -->
                <div class="d219-nomination-cta">
                    <div class="d219-nomination-cta-content">
                        <h3><i class="fa-solid fa-bullhorn"></i> Interested in Leadership?</h3>
                        <p>To learn more about District 219 leadership positions or to nominate yourself or someone else, visit the District Leadership Committee page.</p>
                    </div>
                    <a href="/dlc" class="d219-btn d219-btn-cta">
                        <i class="fa-solid fa-hand"></i> I Want to Help
                    </a>
                </div>
            </div>
            
            <div class="d219-info-grid">
                <div class="d219-info-card">
                    <h3><i class="fa-solid fa-users"></i> For Members</h3>
                    <p>Your club membership continues seamlessly. You'll have access to new networking opportunities and a larger community of fellow Toastmasters across four states.</p>
                </div>
                <div class="d219-info-card">
                    <h3><i class="fa-solid fa-building"></i> For Clubs</h3>
                    <p>Clubs will be organized into new Areas and Divisions. Your club number, meeting schedule, and operations continue unchanged.</p>
                </div>
                <div class="d219-info-card">
                    <h3><i class="fa-solid fa-trophy"></i> For Contests</h3>
                    <p>Speech contests continue as usual at club, Area, Division, and District levels. District winners proceed to quarter finals per the Speech Contest Rulebook.</p>
                </div>
            </div>
        </div>
    </section>

    
    <?php if ($has_slides) : ?>
    <!-- Presentation Slides -->
    <section class="d219-slides-section">
        <div class="d219-container">
            <h2>About the Transition</h2>
            <p class="d219-slides-intro">Learn more about what the District 219 transition means for you.</p>
            
            <div class="d219-slideshow">
                <div class="d219-slide-main">
                    <button type="button" class="d219-slide-nav d219-slide-prev" id="d219-prev"><i class="fa-solid fa-chevron-left"></i></button>
                    <div class="d219-slide-viewer" id="d219-slide-viewer">
                        <?php foreach ($slides as $i => $slide) : ?>
                            <img src="<?php echo esc_url($slide['src']); ?>" alt="<?php echo esc_attr($slide['title']); ?>" class="d219-slide-image <?php echo $i === 0 ? 'd219-active' : ''; ?>" data-index="<?php echo $i; ?>">
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="d219-slide-nav d219-slide-next" id="d219-next"><i class="fa-solid fa-chevron-right"></i></button>
                </div>
                <div class="d219-slide-counter"><span id="d219-current-slide">1</span> / <?php echo count($slides); ?></div>
                <div class="d219-slide-thumbnails" id="d219-thumbnails">
                    <?php foreach ($slides as $i => $slide) : ?>
                        <button type="button" class="d219-thumbnail <?php echo $i === 0 ? 'd219-active' : ''; ?>" data-index="<?php echo $i; ?>" title="<?php echo esc_attr($slide['title']); ?>">
                            <img src="<?php echo esc_url($slide['src']); ?>" alt="<?php echo esc_attr($slide['title']); ?>">
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <?php if ($slides_pdf) : ?>
            <div class="d219-slides-download">
                <a href="<?php echo esc_url($slides_pdf['url']); ?>" class="d219-btn d219-btn-download" download>
                    <i class="fa-solid fa-file-pdf"></i> Download Presentation (PDF)
                </a>
            </div>
            <?php endif; ?>
        </div>
    </section>
    <?php endif; ?>
    
    <!-- Territory Maps -->
    <section class="d219-map-section">
        <div class="d219-container">
            <h2>District 219 Territory</h2>
            
            <div class="d219-maps-grid">
                <div class="d219-map-card">
                    <h3>National Overview</h3>
                    <p>District 219 is part of <strong>Region Realignment Group 10</strong> in the 2026-2027 restructuring.</p>
                    <div class="d219-map-frame">
                        <img src="<?php echo esc_url($maps_url . 'national-overview.webp'); ?>" alt="2026-2027 District Realignment Map - United States">
                    </div>
                </div>
                <div class="d219-map-card">
                    <h3>Group 10 Region</h3>
                    <p>District 219 (shown in burgundy) covers parts of <strong>Ohio, Pennsylvania, West Virginia, and Maryland</strong>.</p>
                    <div class="d219-map-frame">
                        <img src="<?php echo esc_url($maps_url . 'group10-region.webp'); ?>" alt="Group 10 Regional Map - District 219">
                    </div>
                </div>
            </div>
            
            <div class="d219-boundaries">
                <h3><i class="fa-solid fa-map-location-dot"></i> District 219 Boundaries</h3>
                <ul class="d219-boundary-list">
                    <li><strong>Maryland:</strong> Allegany and Garrett counties</li>
                    <li><strong>Ohio:</strong> North and east of Erie, Huron, Crawford, Wyandot, Hardin, Marion, Knox, Holmes, Tuscarawas, Harrison, and Jefferson counties</li>
                    <li><strong>Pennsylvania:</strong> West of Tioga, Lycoming, Union, Snyder, Juniata, Perry, Huntingdon, and Fulton counties</li>
                    <li><strong>West Virginia:</strong> North of Tyler, Doddridge, Harrison, Upshur, Barbour, Tucker, Grant, Hardy, Hampshire, and Morgan counties</li>
                </ul>
                <a href="https://www.toastmasters.org/membership/leadership/districtrealignment/districtmaps" class="d219-map-link" target="_blank" rel="noopener">
                    <i class="fa-solid fa-external-link"></i> View All District Maps on Toastmasters.org
                </a>
            </div>
        </div>
    </section>

    <!-- Division & Area Alignment (includes Club Directory) -->
    <section class="d219-alignment-section" id="alignment">
        <div class="d219-container">
            <h2>Division &amp; Area Alignment</h2>
            <p class="d219-alignment-intro">This is the <strong>proposed alignment</strong> from the District 219 Alignment Committee. It will be voted on at the <strong>Business Meeting on April 27, 2026</strong>. The proposed plan includes <strong>6 Divisions</strong> and <strong>27 Areas</strong> across Ohio, Pennsylvania, West Virginia, and Maryland.</p>
            <p class="d219-alignment-contact">Questions? Contact Alignment Committee Chair <a href="#committees"><strong>Dave Wiley</strong></a></p>

            <!-- Realignment Committee Recognition -->
            <div class="d219-alignment-committee-compact">
                <p class="d219-committee-credit"><strong>Alignment Committee:</strong> Leonard Taylor, Steff Hill, Allison Blakeman, William McGee, Stephanie Scott, Carly Chiavaroli, Tamika Leslie</p>
                <p class="d219-committee-credit"><strong>Advisors:</strong> Dave Wiley, Deonna Moore Taylor, Kathy Wolfe, Jing Humphreys, Melissa McGavick, Rhonda Mauer, Lorie Davis, Javi Diaz</p>
            </div>

            <!-- Stat Cards Row -->
            <div class="d219-alignment-stats-row">
                <div class="d219-alignment-stat-card">
                    <span class="d219-stat-number">133</span>
                    <span class="d219-stat-label">Clubs</span>
                </div>
                <div class="d219-alignment-stat-card">
                    <span class="d219-stat-number">6</span>
                    <span class="d219-stat-label">Divisions</span>
                </div>
                <div class="d219-alignment-stat-card">
                    <span class="d219-stat-number">27</span>
                    <span class="d219-stat-label">Areas</span>
                </div>
                <div class="d219-alignment-stat-card">
                    <span class="d219-stat-number">4</span>
                    <span class="d219-stat-label">States</span>
                </div>
            </div>

            <!-- Maps Row -->
            <div class="d219-alignment-maps-row">
                <div class="d219-alignment-map-card">
                    <h3><i class="fa-solid fa-map"></i> Division Map</h3>
                    <div class="d219-alignment-map-frame">
                        <iframe src="https://www.google.com/maps/d/embed?mid=1d5MjKaMATCNwMtxML-ksDYRDFt15tjs&ehbc=2E312F" width="100%" height="400" style="border:0; border-radius:6px;" allowfullscreen loading="lazy"></iframe>
                    </div>
                    <div class="d219-alignment-map-links">
                        <a href="https://www.google.com/maps/d/edit?mid=1d5MjKaMATCNwMtxML-ksDYRDFt15tjs&usp=sharing" class="d219-btn d219-btn-map" target="_blank" rel="noopener">
                            <i class="fa-solid fa-up-right-from-square"></i> Open Full Division Map
                        </a>
                    </div>
                </div>
                <div class="d219-alignment-map-card">
                    <h3><i class="fa-solid fa-location-dot"></i> Area Map</h3>
                    <div class="d219-alignment-map-frame">
                        <iframe src="https://www.google.com/maps/d/embed?mid=1xdJzntRUC5z-HPGGi0f45mqam_qjZHU&ehbc=2E312F" width="100%" height="400" style="border:0; border-radius:6px;" allowfullscreen loading="lazy"></iframe>
                    </div>
                    <div class="d219-alignment-map-links">
                        <a href="https://www.google.com/maps/d/edit?mid=1xdJzntRUC5z-HPGGi0f45mqam_qjZHU&usp=sharing" class="d219-btn d219-btn-map" target="_blank" rel="noopener">
                            <i class="fa-solid fa-up-right-from-square"></i> Open Full Area Map
                        </a>
                    </div>
                </div>
            </div>

            <!-- Town Hall Callout -->
            <div class="d219-townhall-callout">
                <div class="d219-townhall-icon"><i class="fa-solid fa-comments"></i></div>
                <div class="d219-townhall-details">
                    <strong>Alignment Town Hall Q&amp;A</strong>
                    <span>March 12, 2026 &middot; 8:00 PM – 9:00 PM via Zoom</span>
                    <span class="d219-townhall-desc">Open Q&amp;A about divisions, areas, and club placement</span>
                </div>
                <a href="https://us02web.zoom.us/j/84094774161" class="d219-btn d219-btn-map" target="_blank" rel="noopener">
                    <i class="fa-solid fa-video"></i> Join on Zoom
                </a>
            </div>

            <!-- Division Breakdown (clickable to filter table) -->
            <div class="d219-division-summary">
                <h3><i class="fa-solid fa-sitemap"></i> Division Breakdown <span class="d219-div-hint">Click a division to filter the table below</span></h3>
                <div class="d219-division-cards">
                    <div class="d219-div-card" data-division="A" role="button" tabindex="0"><strong>Division A</strong><span>5 Areas (A10–A14) &middot; 28 Clubs</span><span class="d219-div-region">Western &amp; Central OH</span></div>
                    <div class="d219-div-card" data-division="B" role="button" tabindex="0"><strong>Division B</strong><span>5 Areas (B20–B24) &middot; 23 Clubs</span><span class="d219-div-region">Eastern OH &amp; Erie, PA</span></div>
                    <div class="d219-div-card" data-division="C" role="button" tabindex="0"><strong>Division C</strong><span>5 Areas (C30–C34) &middot; 26 Clubs</span><span class="d219-div-region">Akron, Canton &amp; Southern OH</span></div>
                    <div class="d219-div-card" data-division="D" role="button" tabindex="0"><strong>Division D</strong><span>4 Areas (D40–D43) &middot; 19 Clubs</span><span class="d219-div-region">North &amp; West Pittsburgh, PA</span></div>
                    <div class="d219-div-card" data-division="E" role="button" tabindex="0"><strong>Division E</strong><span>4 Areas (E50–E53) &middot; 19 Clubs</span><span class="d219-div-region">Central &amp; South Pittsburgh, PA</span></div>
                    <div class="d219-div-card" data-division="F" role="button" tabindex="0"><strong>Division F</strong><span>4 Areas (F60–F63) &middot; 18 Clubs</span><span class="d219-div-region">Central PA, WV &amp; MD</span></div>
                </div>
            </div>

            <!-- Club Directory (part of alignment) -->
            <div class="d219-clubs-directory" id="clubs">
                <h3><i class="fa-solid fa-address-book"></i> Club Directory</h3>

                <!-- Search + Filters (always visible) -->
                <div class="d219-clubs-controls">
                    <div class="d219-clubs-search">
                        <i class="fa-solid fa-search"></i>
                        <input type="text" id="d219-club-search" placeholder="Search 133 clubs by name, number, city, or state...">
                    </div>
                    <div class="d219-clubs-filters">
                        <select id="d219-filter-division">
                            <option value="">All Divisions</option>
                            <option value="A">Division A</option>
                            <option value="B">Division B</option>
                            <option value="C">Division C</option>
                            <option value="D">Division D</option>
                            <option value="E">Division E</option>
                            <option value="F">Division F</option>
                        </select>
                        <select id="d219-filter-format">
                            <option value="">All Formats</option>
                            <option value="In Person">In Person</option>
                            <option value="Hybrid">Hybrid</option>
                            <option value="Online">Online</option>
                        </select>
                        <select id="d219-filter-state">
                            <option value="">All States</option>
                            <option value="Ohio">Ohio</option>
                            <option value="Pennsylvania">Pennsylvania</option>
                            <option value="West Virginia">West Virginia</option>
                            <option value="Maryland">Maryland</option>
                            <option value="Georgia">Georgia</option>
                            <option value="Washington">Washington</option>
                        </select>
                    </div>
                </div>

                <!-- Table (hidden until search or browse) -->
                <div class="d219-clubs-expanded" id="d219-clubs-expanded" style="display:none;">
                    <div class="d219-clubs-count">
                        Showing <strong id="d219-clubs-visible">133</strong> of 133 clubs
                        <button class="d219-clubs-collapse-btn" id="d219-btn-collapse" title="Collapse table"><i class="fa-solid fa-chevron-up"></i> Collapse</button>
                    </div>

                    <div class="d219-clubs-table-wrap">
                        <table class="d219-clubs-table" id="d219-clubs-table">
                            <thead>
                                <tr>
                                    <th data-sort="div" class="d219-sortable">Div <i class="fa-solid fa-sort"></i></th>
                                    <th data-sort="area" class="d219-sortable">Area <i class="fa-solid fa-sort"></i></th>
                                    <th data-sort="num" class="d219-sortable">Club # <i class="fa-solid fa-sort"></i></th>
                                    <th data-sort="name" class="d219-sortable d219-sort-active d219-sort-asc">Club Name <i class="fa-solid fa-sort-up"></i></th>
                                    <th data-sort="city" class="d219-sortable">City <i class="fa-solid fa-sort"></i></th>
                                    <th data-sort="state" class="d219-sortable">State <i class="fa-solid fa-sort"></i></th>
                                    <th data-sort="format" class="d219-sortable">Format <i class="fa-solid fa-sort"></i></th>
                                </tr>
                            </thead>
                            <tbody id="d219-clubs-tbody">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Downloads -->
            <div class="d219-alignment-downloads">
                <a href="<?php echo esc_url(D219_ASSETS_URL . 'Toastmasters 219 Realignment.pptx'); ?>" class="d219-btn d219-btn-download" download>
                    <i class="fa-solid fa-file-powerpoint"></i> Download Alignment Presentation
                </a>
                <a href="<?php echo esc_url(D219_ASSETS_URL . 'District 219 club list Formatted.xlsx'); ?>" class="d219-btn d219-btn-download" download>
                    <i class="fa-solid fa-file-excel"></i> Download Club List Spreadsheet
                </a>
            </div>
        </div>
    </section>


    <!-- Important Dates -->
    <section class="d219-dates-section">
        <div class="d219-container">
            <h2>Important Dates</h2>
            <div class="d219-dates-grid">
                <div class="d219-date-card">
                    <div class="d219-date-icon"><i class="fa-solid fa-clipboard-list"></i></div>
                    <h3>Nomination Deadline</h3>
                    <p class="d219-date-value">February 25, 2026</p>
                    <p class="d219-date-desc">Submit nominations for District 219 leadership positions</p>
                    <div class="d219-date-action">
                        <a href="/dlc" class="d219-date-btn">
                            <i class="fa-solid fa-hand"></i> I Want to Help
                        </a>
                    </div>
                </div>
                <div class="d219-date-card">
                    <div class="d219-date-icon"><i class="fa-solid fa-comments"></i></div>
                    <h3>Alignment Town Hall Q&amp;A</h3>
                    <p class="d219-date-value">March 12, 2026</p>
                    <p class="d219-date-time">8:00 PM – 9:00 PM via Zoom</p>
                    <p class="d219-date-desc">Open Q&amp;A about District 219 alignment — divisions, areas, and club placement</p>
                    <div class="d219-date-action">
                        <a href="https://us02web.zoom.us/j/84094774161" class="d219-date-btn" target="_blank" rel="noopener">
                            <i class="fa-solid fa-video"></i> Join on Zoom
                        </a>
                    </div>
                </div>
                <div class="d219-date-card">
                    <div class="d219-date-icon"><i class="fa-solid fa-calendar-check"></i></div>
                    <h3>Business Meeting</h3>
                    <p class="d219-date-value">April 27, 2026</p>
                    <p class="d219-date-time">7:00 PM via Zoom</p>
                    <p class="d219-date-desc">District 219 Election Meeting &amp; Alignment Vote</p>
                    <div class="d219-date-action">
                        <?php if (!empty($zoom_link)) : ?>
                            <a href="<?php echo esc_url($zoom_link); ?>" class="d219-date-btn" target="_blank" rel="noopener">
                                <i class="fa-solid fa-video"></i> Register Now
                            </a>
                        <?php else : ?>
                            <span class="d219-date-btn d219-disabled"><i class="fa-solid fa-clock"></i> Registration Coming Soon</span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="d219-date-card">
                    <div class="d219-date-icon"><i class="fa-solid fa-flag-checkered"></i></div>
                    <h3>District 219 Begins</h3>
                    <p class="d219-date-value">July 1, 2026</p>
                    <p class="d219-date-time">2:00 AM Eastern</p>
                    <p class="d219-date-desc">First day of the new Toastmasters year for District 219</p>
                    <div class="d219-date-action">
                        <a href="#clubs" class="d219-date-btn">
                            <i class="fa-solid fa-address-book"></i> View Clubs
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Committee Organization -->
    <section class="d219-committee-section" id="committees">
        <div class="d219-container">
            <h2>Transition Committee</h2>
            
            <!-- Group Chair -->
            <div class="d219-group-chair">
                <div class="d219-chair-photo">
                    <img src="<?php echo esc_url($headshots_url . $committees['group']['photo']); ?>" alt="<?php echo esc_attr($committees['group']['name']); ?>">
                </div>
                <h3><?php echo esc_html($committees['group']['title']); ?></h3>
                <p class="d219-chair-name"><?php echo esc_html($committees['group']['name']); ?></p>
                <div class="d219-chair-links">
                    <a href="<?php echo esc_url($committees['group']['linkedin']); ?>" class="d219-chair-link" target="_blank" rel="noopener" title="LinkedIn"><i class="fa-brands fa-linkedin"></i></a>
                </div>
            </div>
            
            <!-- Committee Chairs with Full Descriptions -->
            <div class="d219-chairs-grid">
                <?php foreach (array('alignment', 'leadership', 'transition') as $key) : $c = $committees[$key]; ?>
                <div class="d219-chair-card">
                    <div class="d219-chair-photo">
                        <img src="<?php echo esc_url($headshots_url . $c['photo']); ?>" alt="<?php echo esc_attr($c['name']); ?>">
                    </div>
                    <h4><?php echo esc_html($c['title']); ?></h4>
                    <p class="d219-chair-name"><?php echo esc_html($c['name']); ?></p>
                    <div class="d219-chair-links">
                        <a href="mailto:<?php echo esc_attr($c['email']); ?>" class="d219-chair-link" title="Email"><i class="fa-solid fa-envelope"></i></a>
                        <a href="<?php echo esc_url($c['linkedin']); ?>" class="d219-chair-link" target="_blank" rel="noopener" title="LinkedIn"><i class="fa-brands fa-linkedin"></i></a>
                    </div>
                    <p class="d219-chair-desc"><?php echo esc_html($c['desc_full']); ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Get Involved CTA -->
    <section class="d219-cta-section" id="get-involved">
        <div class="d219-container">
            <h2>Get Involved</h2>
            <p>To learn more about District 219 leadership positions or to nominate yourself or someone else, visit the District Leadership Committee page.</p>
            <div class="d219-cta-buttons">
                <a href="/dlc" class="d219-btn d219-btn-primary">
                    <i class="fa-solid fa-hand"></i> I Want to Help
                </a>
                <a href="mailto:district219dlc1@gmail.com" class="d219-btn d219-btn-secondary">
                    <i class="fa-solid fa-envelope"></i> Contact Us
                </a>
            </div>
        </div>
    </section>
    
    <!-- Resources Footer -->
    <section class="d219-resources-footer">
        <div class="d219-container">
            <p class="d219-resources-label">Toastmasters International Resources</p>
            <div class="d219-resources-links">
                <a href="https://www.toastmasters.org/membership/leadership/districtrealignment/districtmaps" target="_blank" rel="noopener">District Maps</a>
                <a href="#alignment">Division Alignment</a>
                <a href="#clubs">Club Directory</a>
                <a href="/dlc">Leadership Nominations</a>
                <a href="https://www.toastmasters.org/membership/leadership/districtrealignment" target="_blank" rel="noopener">About Realignment</a>
                <a href="https://www.toastmasters.org/leadership-central/district-leader-tools/district-realignment/district-realignment-faq" target="_blank" rel="noopener">Realignment FAQ</a>
            </div>
            <p class="d219-disclaimer">The information on this website is for the sole use of Toastmasters' members, for Toastmasters business only. It is not to be used for solicitation and distribution of non&#8209;Toastmasters material or information.</p>
        </div>
    </section>

    <!-- Floating Bottom Nav (mobile) -->
    <nav class="d219-floating-nav" id="d219-floating-nav" aria-label="Quick navigation">
        <a href="#" class="d219-fnav-item d219-fnav-active" data-section="top">
            <i class="fa-solid fa-circle-info"></i>
            <span>D219</span>
        </a>
        <a href="#alignment" class="d219-fnav-item" data-section="alignment">
            <i class="fa-solid fa-map"></i>
            <span>Alignment</span>
        </a>
        <a href="#clubs" class="d219-fnav-item" data-section="clubs">
            <i class="fa-solid fa-address-book"></i>
            <span>Clubs</span>
        </a>
        <a href="/dlc" class="d219-fnav-item">
            <i class="fa-solid fa-hand"></i>
            <span>Nominate</span>
        </a>
    </nav>

</div>

<?php if ($has_slides) : ?>
<script>
(function() {
    var slides = document.querySelectorAll('.d219-slide-image');
    var thumbs = document.querySelectorAll('.d219-thumbnail');
    var counter = document.getElementById('d219-current-slide');
    var currentIndex = 0, totalSlides = slides.length;
    
    function showSlide(index) {
        if (index < 0) index = totalSlides - 1;
        if (index >= totalSlides) index = 0;
        currentIndex = index;
        slides.forEach(function(s, i) { s.classList.toggle('d219-active', i === index); });
        thumbs.forEach(function(t, i) { t.classList.toggle('d219-active', i === index); });
        if (counter) counter.textContent = index + 1;
        if (thumbs[index]) thumbs[index].scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
    }
    
    var prevBtn = document.getElementById('d219-prev');
    var nextBtn = document.getElementById('d219-next');
    if (prevBtn) prevBtn.onclick = function() { showSlide(currentIndex - 1); };
    if (nextBtn) nextBtn.onclick = function() { showSlide(currentIndex + 1); };
    thumbs.forEach(function(thumb) { thumb.onclick = function() { showSlide(parseInt(this.dataset.index)); }; });
    
    var slideshowEl = document.querySelector('.d219-slideshow');
    if (slideshowEl) {
        slideshowEl.setAttribute('tabindex', '0');
        slideshowEl.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowLeft') { e.preventDefault(); showSlide(currentIndex - 1); }
            if (e.key === 'ArrowRight') { e.preventDefault(); showSlide(currentIndex + 1); }
        });
    }
    
    var viewer = document.getElementById('d219-slide-viewer');
    var touchStartX = 0;
    if (viewer) {
        viewer.addEventListener('touchstart', function(e) { touchStartX = e.touches[0].clientX; });
        viewer.addEventListener('touchend', function(e) {
            var diff = touchStartX - e.changedTouches[0].clientX;
            if (Math.abs(diff) > 50) { if (diff > 0) showSlide(currentIndex + 1); else showSlide(currentIndex - 1); }
        });
    }
})();
</script>
<?php endif; ?>

<script>
(function() {
    var clubs = <?php echo file_get_contents(D219_ASSETS_DIR . 'clubs.json'); ?>;

    var tbody = document.getElementById('d219-clubs-tbody');
    var searchInput = document.getElementById('d219-club-search');
    var divFilter = document.getElementById('d219-filter-division');
    var fmtFilter = document.getElementById('d219-filter-format');
    var stateFilter = document.getElementById('d219-filter-state');
    var countEl = document.getElementById('d219-clubs-visible');
    var expandedEl = document.getElementById('d219-clubs-expanded');
    var collapseBtn = document.getElementById('d219-btn-collapse');
    var currentSort = { key: 'name', asc: true };
    var tableVisible = false;

    function showTable() {
        if (tableVisible) return;
        tableVisible = true;
        expandedEl.style.display = 'block';
        renderTable();
    }

    function hideTable() {
        tableVisible = false;
        expandedEl.style.display = 'none';
        divFilter.value = '';
        fmtFilter.value = '';
        stateFilter.value = '';
        searchInput.value = '';
        document.querySelectorAll('.d219-div-card').forEach(function(c) { c.classList.remove('d219-div-active'); });
    }

    collapseBtn.addEventListener('click', hideTable);

    // Typing in search auto-opens the table
    searchInput.addEventListener('input', function() {
        if (this.value.length > 0 && !tableVisible) {
            showTable();
        }
        if (tableVisible) renderTable();
    });


    function formatBadge(fmt) {
        if (!fmt || fmt === 'NEW') return fmt === 'NEW' ? '<span class="d219-badge d219-badge-new">New</span>' : '—';
        var cls = fmt === 'Hybrid' ? 'hybrid' : (fmt === 'Online' ? 'online' : 'inperson');
        return '<span class="d219-badge d219-badge-' + cls + '">' + fmt + '</span>';
    }

    function renderTable() {
        var search = searchInput.value.toLowerCase();
        var divVal = divFilter.value;
        var fmtVal = fmtFilter.value;
        var stateVal = stateFilter.value;

        var filtered = clubs.filter(function(c) {
            if (divVal && c.div !== divVal) return false;
            if (fmtVal && c.format !== fmtVal) return false;
            if (stateVal && c.state !== stateVal) return false;
            if (search) {
                var haystack = (c.name + ' ' + c.city + ' ' + c.state + ' ' + c.area + ' ' + c.div + ' ' + c.num).toLowerCase();
                if (haystack.indexOf(search) === -1) return false;
            }
            return true;
        });

        filtered.sort(function(a, b) {
            var va = (a[currentSort.key] || '').toString().toLowerCase();
            var vb = (b[currentSort.key] || '').toString().toLowerCase();
            if (va < vb) return currentSort.asc ? -1 : 1;
            if (va > vb) return currentSort.asc ? 1 : -1;
            return 0;
        });

        var html = '';
        for (var i = 0; i < filtered.length; i++) {
            var c = filtered[i];
            var rowClass = c['new'] ? ' class="d219-new-club"' : '';
            html += '<tr' + rowClass + '>';
            html += '<td>' + c.div + '</td>';
            html += '<td>' + c.area + '</td>';
            html += '<td>' + (c.num || '—') + '</td>';
            html += '<td>' + c.name + (c['new'] ? ' <span class="d219-badge d219-badge-new">New</span>' : '') + '</td>';
            html += '<td>' + (c.city || '—') + '</td>';
            html += '<td>' + (c.state || '—') + '</td>';
            html += '<td>' + formatBadge(c.format) + '</td>';
            html += '</tr>';
        }

        tbody.innerHTML = html || '<tr><td colspan="7" style="text-align:center;padding:24px;color:#888;">No clubs match your search.</td></tr>';
        countEl.textContent = filtered.length;
    }

    // Sort on header click
    document.querySelectorAll('.d219-sortable').forEach(function(th) {
        th.addEventListener('click', function() {
            var key = this.dataset.sort;
            if (currentSort.key === key) {
                currentSort.asc = !currentSort.asc;
            } else {
                currentSort.key = key;
                currentSort.asc = true;
            }
            document.querySelectorAll('.d219-sortable').forEach(function(el) {
                el.classList.remove('d219-sort-active', 'd219-sort-asc', 'd219-sort-desc');
                el.querySelector('i').className = 'fa-solid fa-sort';
            });
            this.classList.add('d219-sort-active', currentSort.asc ? 'd219-sort-asc' : 'd219-sort-desc');
            this.querySelector('i').className = 'fa-solid ' + (currentSort.asc ? 'fa-sort-up' : 'fa-sort-down');
            renderTable();
        });
    });

    divFilter.addEventListener('change', function() {
        document.querySelectorAll('.d219-div-card').forEach(function(card) {
            card.classList.toggle('d219-div-active', card.dataset.division === divFilter.value);
        });
        if (!tableVisible) showTable(); else renderTable();
    });
    fmtFilter.addEventListener('change', function() {
        if (!tableVisible) showTable(); else renderTable();
    });
    stateFilter.addEventListener('change', function() {
        if (!tableVisible) showTable(); else renderTable();
    });

    // Division card click → expand table + filter + scroll
    document.querySelectorAll('.d219-div-card').forEach(function(card) {
        card.addEventListener('click', function() {
            var div = this.dataset.division;
            var isActive = this.classList.contains('d219-div-active');
            if (isActive) {
                divFilter.value = '';
                this.classList.remove('d219-div-active');
            } else {
                divFilter.value = div;
                document.querySelectorAll('.d219-div-card').forEach(function(c) { c.classList.remove('d219-div-active'); });
                this.classList.add('d219-div-active');
            }
            showTable();
            renderTable();
            var clubsEl = document.getElementById('clubs');
            if (clubsEl) clubsEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
        card.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); this.click(); }
        });
    });

    // Floating nav: highlight active section on scroll
    var floatingNav = document.getElementById('d219-floating-nav');
    if (floatingNav) {
        var navItems = floatingNav.querySelectorAll('.d219-fnav-item[data-section]');
        var sections = [];
        navItems.forEach(function(item) {
            var id = item.dataset.section;
            var el = id === 'top' ? document.querySelector('.d219-hero') : document.getElementById(id);
            if (el) sections.push({ id: id, el: el, navItem: item });
        });

        function updateActiveNav() {
            var scrollY = window.scrollY + window.innerHeight / 3;
            var active = sections[0];
            for (var i = 0; i < sections.length; i++) {
                if (sections[i].el.offsetTop <= scrollY) active = sections[i];
            }
            navItems.forEach(function(item) { item.classList.remove('d219-fnav-active'); });
            if (active) active.navItem.classList.add('d219-fnav-active');
        }

        var scrollTimer;
        window.addEventListener('scroll', function() {
            clearTimeout(scrollTimer);
            scrollTimer = setTimeout(updateActiveNav, 50);
        });

        // Smooth scroll for anchor links
        navItems.forEach(function(item) {
            item.addEventListener('click', function(e) {
                var href = this.getAttribute('href');
                if (href === '#' || href.charAt(0) === '#') {
                    e.preventDefault();
                    var target = href === '#' ? document.querySelector('.d219-hero') : document.querySelector(href);
                    if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });

        // Show nav only after scrolling past hero
        var hero = document.querySelector('.d219-hero');
        function checkNavVisibility() {
            if (!hero) return;
            if (window.scrollY > hero.offsetHeight) {
                floatingNav.classList.add('d219-fnav-visible');
            } else {
                floatingNav.classList.remove('d219-fnav-visible');
            }
        }
        window.addEventListener('scroll', function() {
            clearTimeout(scrollTimer);
            scrollTimer = setTimeout(function() { updateActiveNav(); checkNavVisibility(); }, 50);
        });
        checkNavVisibility();
    }
})();
</script>


<?php get_footer(); ?>
