<?php
/**
 * Template: Email Preview for D219 Candidate Announcement
 * Accessible at /staging/email — public before publish, admin-only after
 * Generates Constant Contact-ready HTML email with copy helpers
 */

if (!defined('ABSPATH')) exit;

$email_url = D219_ASSETS_URL . 'email/';
$roles = d219_get_candidates();
$bios = d219_get_candidate_bios();
$committees = d219_get_committees();
$dlc_chair = $committees['leadership'];

// Determine base URLs — email always links to live (non-staging) pages
$site_url = home_url();
$transition_url = $site_url . '/transition';
$dlc_url = $site_url . '/dlc';

// Build candidate list grouped by role
$role_groups = array();
foreach ($roles as $role) {
    $group = array(
        'role' => $role['role'],
        'region' => isset($role['region']) ? $role['region'] : '',
        'contested' => count($role['candidates']) > 1,
        'candidates' => array(),
    );
    foreach ($role['candidates'] as $c) {
        $slug = sanitize_title(explode(',', $c['name'])[0]);
        $bio = isset($bios[$slug]) ? $bios[$slug] : null;
        if (!$bio) continue;
        $group['candidates'][] = array(
            'name' => $bio['name'],
            'credentials' => $bio['credentials'],
            'slug' => $slug,
            'photo_jpg' => $email_url . $slug . '.jpg',
            'profile_url' => $dlc_url . '#' . $slug,
        );
    }
    $role_groups[] = $group;
}

$subject_line = 'Meet Your District 219 Candidates — Election April 27th';

// Brand colors
$navy = '#004165';
$maroon = '#772432';
$gold = '#F2DF74';
$navy_dark = '#002d47';
$navy_light = '#e8f1f5';
$maroon_light = '#f5eaed';
$gold_light = '#fdf8e8';
$text_dark = '#333333';
$text_muted = '#666666';
$text_light = '#999999';

// Buffer the email HTML
ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title><?php echo esc_html($subject_line); ?></title>
<!--[if !mso]><!-->
<link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;600;700&family=Montserrat:wght@600;700&display=swap" rel="stylesheet" />
<!--<![endif]-->
<!--[if mso]>
<style type="text/css">
table {border-collapse:collapse;border-spacing:0;margin:0;}
div, td {padding:0;}
div {margin:0 !important;}
</style>
<noscript>
<xml>
<o:OfficeDocumentSettings>
<o:PixelsPerInch>96</o:PixelsPerInch>
</o:OfficeDocumentSettings>
</xml>
</noscript>
<![endif]-->
</head>
<body style="margin:0;padding:0;background-color:#f4f4f4;font-family:'Source Sans Pro',Arial,'Helvetica Neue',Helvetica,sans-serif;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;">

<!-- Preheader text (hidden, shows in inbox preview) -->
<div style="display:none;font-size:1px;color:#f4f4f4;line-height:1px;max-height:0px;max-width:0px;opacity:0;overflow:hidden;">
The nominated slate for District 219 leadership has been announced. See who is running and learn about each candidate before the April 27th election.
</div>

<!-- Tracking pixel — near top for reliable open tracking -->
[[trackingImage]]

<!-- Outer wrapper -->
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#f4f4f4;">
<tr><td align="center" style="padding:20px 10px;">

<!-- Email container 600px -->
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="600" style="max-width:600px;width:100%;background-color:#ffffff;border-radius:8px;overflow:hidden;">

<!-- ====== BANNER ====== -->
<tr>
<td style="padding:0;background-color:<?php echo $navy; ?>;">
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
<td style="padding:24px 30px;text-align:center;">
<a href="<?php echo esc_url($dlc_url); ?>" target="_blank" style="text-decoration:none;">
<p style="margin:0 0 4px;font-size:13px;color:<?php echo $gold; ?>;text-transform:uppercase;letter-spacing:2px;font-family:'Montserrat',Arial,Helvetica,sans-serif;font-weight:600;">Toastmasters International</p>
<p style="margin:0;font-size:26px;color:#ffffff;font-weight:700;font-family:'Montserrat',Arial,Helvetica,sans-serif;letter-spacing:0.5px;">District 219</p>
<p style="margin:6px 0 0;font-size:12px;color:<?php echo $gold; ?>;font-family:'Source Sans Pro',Arial,Helvetica,sans-serif;letter-spacing:1px;">DISTRICT 10 &amp; DISTRICT 13 &bull; UNITED AS ONE</p>
</a>
</td>
</tr>
</table>
</td>
</tr>

<!-- ====== GREETING ====== -->
<tr>
<td style="padding:30px 30px 10px;">
<h1 style="margin:0 0 12px;font-size:24px;font-weight:700;color:<?php echo $navy; ?>;font-family:'Montserrat',Arial,Helvetica,sans-serif;text-align:center;">Meet Your District 219 Candidates</h1>
<p style="margin:0 0 6px;font-size:16px;color:<?php echo $text_dark; ?>;line-height:1.5;font-family:'Source Sans Pro',Arial,Helvetica,sans-serif;">Hello [[First Name]],</p>
<p style="margin:0;font-size:16px;color:<?php echo $text_dark; ?>;line-height:1.5;font-family:'Source Sans Pro',Arial,Helvetica,sans-serif;">The District Leadership Committee is pleased to announce the nominated slate of candidates for <strong>District 219</strong> leadership for the <strong>2026&ndash;2027</strong> Toastmasters year.</p>
</td>
</tr>

<!-- ====== DLC CHAIR MESSAGE ====== -->
<tr>
<td style="padding:16px 30px 10px;">
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:<?php echo $gold_light; ?>;border-radius:8px;border-left:4px solid <?php echo $gold; ?>;">
<tr>
<td style="padding:18px 24px;">
<p style="margin:0 0 10px;font-size:15px;color:<?php echo $text_dark; ?>;line-height:1.6;font-family:'Source Sans Pro',Arial,Helvetica,sans-serif;">The District Leadership Committee for District 219 is pleased to announce the nominated candidates for District Office for the <strong>2026&ndash;2027 Toastmasters year</strong>. These individuals have stepped forward to lead our newly forming district, created from District 10 and District 13.</p>
<p style="margin:0 0 10px;font-size:15px;color:<?php echo $text_dark; ?>;line-height:1.6;font-family:'Source Sans Pro',Arial,Helvetica,sans-serif;">Elections will be held at the <strong>District 219 Business Meeting on April 27, 2026</strong>.</p>
<p style="margin:0 0 10px;font-size:15px;color:<?php echo $text_dark; ?>;line-height:1.6;font-family:'Source Sans Pro',Arial,Helvetica,sans-serif;font-style:italic;">Candidates are listed in alphabetical order by last name. One candidate, Autumn Jose, has been nominated for two roles: Division A Director and Division F Director.</p>
<p style="margin:0;font-size:14px;color:<?php echo $text_muted; ?>;font-style:italic;font-family:'Source Sans Pro',Arial,Helvetica,sans-serif;">&mdash; <?php echo esc_html($dlc_chair['name']); ?>, <?php echo esc_html($dlc_chair['title']); ?></p>
</td>
</tr>
</table>
</td>
</tr>

<!-- ====== ELECTION CTA ====== -->
<tr>
<td style="padding:20px 30px;">
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:<?php echo $navy_light; ?>;border-radius:8px;border:1px solid #c0d6e4;">
<tr>
<td style="padding:18px 24px;text-align:center;">
<p style="margin:0 0 4px;font-size:14px;color:<?php echo $navy; ?>;font-weight:700;text-transform:uppercase;letter-spacing:1px;font-family:'Montserrat',Arial,Helvetica,sans-serif;">District 219 Election Meeting</p>
<p style="margin:0 0 10px;font-size:18px;color:<?php echo $navy; ?>;font-weight:700;font-family:'Montserrat',Arial,Helvetica,sans-serif;">April 27, 2026 &middot; 7:00 PM via Zoom</p>
<p style="margin:0;font-size:14px;color:<?php echo $text_dark; ?>;font-family:'Source Sans Pro',Arial,Helvetica,sans-serif;">All members are encouraged to participate.</p>
</td>
</tr>
</table>
</td>
</tr>

<!-- ====== CANDIDATE SORT NOTE ====== -->
<tr>
<td style="padding:10px 30px 0;text-align:center;">
<p style="margin:0;font-size:13px;color:<?php echo $text_light; ?>;font-style:italic;line-height:1.4;font-family:'Source Sans Pro',Arial,Helvetica,sans-serif;">Candidates are listed in alphabetical order by last name within each role. Roles with more than one candidate are marked as contested.</p>
</td>
</tr>

<!-- ====== CANDIDATE SECTIONS ====== -->
<?php foreach ($role_groups as $group) : ?>
<tr>
<td style="padding:10px 30px 0;">
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
<td style="padding:16px 0 8px;border-bottom:2px solid <?php echo $navy; ?>;">
<h2 style="margin:0;font-size:18px;color:<?php echo $navy; ?>;font-family:'Montserrat',Arial,Helvetica,sans-serif;font-weight:700;">
<?php echo esc_html($group['role']); ?>
<?php if ($group['region']) : ?><span style="font-weight:normal;font-size:14px;color:<?php echo $text_muted; ?>;"> &mdash; <?php echo esc_html($group['region']); ?></span><?php endif; ?>
</h2>
<?php if ($group['contested']) : ?>
<p style="margin:4px 0 0;font-size:13px;color:<?php echo $maroon; ?>;font-weight:bold;">Contested &mdash; <?php echo count($group['candidates']); ?> candidates</p>
<?php endif; ?>
</td>
</tr>
</table>
</td>
</tr>

<?php foreach ($group['candidates'] as $c) : ?>
<tr>
<td style="padding:0 30px;">
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="border-bottom:1px solid #eeeeee;">
<tr>
<td width="80" valign="top" style="padding:12px 16px 12px 0;">
<a href="<?php echo esc_url($c['profile_url']); ?>" target="_blank" style="text-decoration:none;">
<img src="<?php echo esc_url($c['photo_jpg']); ?>" alt="<?php echo esc_attr($c['name']); ?>" width="70" height="70" style="display:block;width:70px;height:70px;border-radius:50%;object-fit:cover;border:2px solid #dddddd;" />
</a>
</td>
<td valign="middle" style="padding:12px 0;font-size:15px;color:<?php echo $text_dark; ?>;line-height:1.4;font-family:'Source Sans Pro',Arial,Helvetica,sans-serif;">
<a href="<?php echo esc_url($c['profile_url']); ?>" target="_blank" style="text-decoration:none;color:<?php echo $navy; ?>;font-size:16px;font-weight:bold;"><?php echo esc_html($c['name']); ?></a><br />
<span style="font-size:13px;color:<?php echo $text_muted; ?>;"><?php echo esc_html($c['credentials']); ?></span>
</td>
</tr>
</table>
</td>
</tr>
<?php endforeach; ?>
<?php endforeach; ?>

<!-- ====== VIEW FULL PROFILES BUTTON ====== -->
<tr>
<td style="padding:30px 30px 10px;text-align:center;">
<table role="presentation" cellpadding="0" cellspacing="0" border="0" align="center">
<tr>
<td style="background-color:<?php echo $navy; ?>;border-radius:6px;">
<a href="<?php echo esc_url($dlc_url); ?>" target="_blank" style="display:inline-block;padding:14px 36px;font-size:16px;font-weight:bold;color:#ffffff;text-decoration:none;font-family:'Source Sans Pro',Arial,Helvetica,sans-serif;">
View Full Candidate Profiles
</a>
</td>
</tr>
</table>
<p style="margin:10px 0 0;font-size:13px;color:<?php echo $text_light; ?>;font-family:'Source Sans Pro',Arial,Helvetica,sans-serif;">Read each candidate&rsquo;s full bio, compare responses side by side, and download their official PDF applicant bios.</p>
</td>
</tr>

<!-- ====== SHOWCASE VIDEOS ====== -->
<tr>
<td style="padding:20px 30px;">
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:<?php echo $gold_light; ?>;border-radius:8px;border:1px solid <?php echo $gold; ?>;">
<tr>
<td style="padding:18px 24px;text-align:center;">
<p style="margin:0 0 6px;font-size:15px;color:<?php echo $navy; ?>;font-weight:700;font-family:'Montserrat',Arial,Helvetica,sans-serif;">Candidate Showcase Videos Coming Soon</p>
<p style="margin:0;font-size:14px;color:<?php echo $text_dark; ?>;line-height:1.5;font-family:'Source Sans Pro',Arial,Helvetica,sans-serif;">After <strong>April 22, 2026</strong>, recorded Candidate Showcase videos will be available on the <a href="<?php echo esc_url($dlc_url); ?>" target="_blank" style="color:<?php echo $navy; ?>;text-decoration:underline;">candidate profiles page</a> so you can hear directly from each candidate before the election.</p>
</td>
</tr>
</table>
</td>
</tr>

<!-- ====== TRANSITION / ALIGNMENT ====== -->
<tr>
<td style="padding:10px 30px 20px;">
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:<?php echo $navy_light; ?>;border-radius:8px;border:1px solid #c0d6e4;">
<tr>
<td style="padding:18px 24px;text-align:center;">
<p style="margin:0 0 6px;font-size:15px;color:<?php echo $navy; ?>;font-weight:700;font-family:'Montserrat',Arial,Helvetica,sans-serif;">District 219 Transition Updates</p>
<p style="margin:0 0 12px;font-size:14px;color:<?php echo $text_dark; ?>;line-height:1.5;font-family:'Source Sans Pro',Arial,Helvetica,sans-serif;">District 10 and District 13 are merging to form <strong>District 219</strong> on July 1, 2026. The proposed club alignment into <strong>6 divisions and 27 areas</strong> is available to review, along with the timeline and transition committee details.</p>
<table role="presentation" cellpadding="0" cellspacing="0" border="0" align="center">
<tr>
<td style="background-color:<?php echo $navy; ?>;border-radius:6px;">
<a href="<?php echo esc_url($transition_url); ?>" target="_blank" style="display:inline-block;padding:10px 24px;font-size:14px;font-weight:bold;color:#ffffff;text-decoration:none;font-family:'Source Sans Pro',Arial,Helvetica,sans-serif;">
View Transition Details and Club Alignment
</a>
</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>

<!-- ====== INTEREST FORM ====== -->
<tr>
<td style="padding:0 30px 20px;">
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:<?php echo $maroon_light; ?>;border-radius:8px;border:1px solid #dcc0c7;">
<tr>
<td style="padding:18px 24px;text-align:center;">
<p style="margin:0 0 6px;font-size:15px;color:<?php echo $maroon; ?>;font-weight:700;font-family:'Montserrat',Arial,Helvetica,sans-serif;">Interested in Serving District 219?</p>
<p style="margin:0 0 12px;font-size:14px;color:<?php echo $text_dark; ?>;line-height:1.5;font-family:'Source Sans Pro',Arial,Helvetica,sans-serif;">While elected positions are on the ballot, there are many <strong>appointed and volunteer roles</strong> available &mdash; Area Directors, Finance Manager, PR Chair, Conference Chair, and more.</p>
<table role="presentation" cellpadding="0" cellspacing="0" border="0" align="center">
<tr>
<td style="background-color:<?php echo $maroon; ?>;border-radius:6px;">
<a href="https://docs.google.com/forms/d/e/1FAIpQLScVoaKQ8Sq8Yp_mTAwsHnahVUAjr9qXdlOV0wvzzdh9f6L-sQ/viewform" target="_blank" style="display:inline-block;padding:10px 24px;font-size:14px;font-weight:bold;color:#ffffff;text-decoration:none;font-family:'Source Sans Pro',Arial,Helvetica,sans-serif;">
Quick Interest Form
</a>
</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>

<!-- ====== FOOTER ====== -->
<tr>
<td style="padding:20px 30px;background-color:<?php echo $navy_dark; ?>;text-align:center;border-radius:0 0 8px 8px;">
<p style="margin:0 0 8px;font-size:14px;color:#ffffff;font-weight:700;font-family:'Montserrat',Arial,Helvetica,sans-serif;">District 219 Toastmasters</p>
<p style="margin:0 0 12px;font-size:13px;color:#bbbbbb;line-height:1.5;font-family:'Source Sans Pro',Arial,Helvetica,sans-serif;">
<a href="<?php echo esc_url($transition_url); ?>" target="_blank" style="color:<?php echo $gold; ?>;text-decoration:underline;">Transition Overview</a> &nbsp;&middot;&nbsp;
<a href="<?php echo esc_url($dlc_url); ?>" target="_blank" style="color:<?php echo $gold; ?>;text-decoration:underline;">Meet the Candidates</a>
</p>
</td>
</tr>

</table>
<!-- /Email container -->

<!-- Constant Contact footer -->
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="600" style="max-width:600px;width:100%;">
<tr>
<td style="padding:16px 30px;text-align:center;">
<p style="margin:0 0 6px;font-size:11px;color:#999999;line-height:1.4;font-family:'Source Sans Pro',Arial,Helvetica,sans-serif;">
This email was sent by [[organizationName]] on behalf of the District 219 Transition effort.<br />
<a href="[[viewAsWebpage]]" style="color:#666666;text-decoration:underline;">View as webpage</a>
</p>
<p style="margin:0;font-size:11px;color:#999999;line-height:1.4;font-family:'Source Sans Pro',Arial,Helvetica,sans-serif;">
<a href="[[unsubscribeUrl]]" style="color:#666666;text-decoration:underline;">Unsubscribe</a> | <a href="[[managePreferencesUrl]]" style="color:#666666;text-decoration:underline;">Manage Preferences</a><br />
[[organizationAddress]]
</p>
</td>
</tr>
</table>

</td></tr>
</table>
<!-- /Outer wrapper -->

</body>
</html>
<?php
$email_html = ob_get_clean();

// Now render the preview page (NOT the email itself — this is the admin wrapper)
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Email Preview — District 219</title>
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #1a202c; color: #e2e8f0; }
.admin-bar { background: #2d3748; padding: 16px 24px; display: flex; align-items: center; gap: 16px; flex-wrap: wrap; border-bottom: 3px solid <?php echo $navy; ?>; }
.admin-bar h1 { font-size: 18px; color: #fff; flex: 1; }
.admin-bar .badge { background: #e53e3e; color: #fff; font-size: 11px; padding: 3px 8px; border-radius: 4px; font-weight: 600; }
.copy-section { background: #2d3748; margin: 20px auto; max-width: 640px; border-radius: 8px; overflow: hidden; }
.copy-header { padding: 12px 20px; background: #4a5568; display: flex; align-items: center; justify-content: space-between; }
.copy-header h3 { font-size: 14px; color: #e2e8f0; }
.copy-btn { background: <?php echo $navy; ?>; color: #fff; border: none; padding: 6px 16px; border-radius: 4px; cursor: pointer; font-size: 13px; font-weight: 600; }
.copy-btn:hover { background: #003350; }
.copy-btn.copied { background: #38a169; }
.copy-content { padding: 12px 20px; background: #1a202c; max-height: 120px; overflow-y: auto; }
.copy-content code { font-size: 12px; color: #a0aec0; word-break: break-all; white-space: pre-wrap; }
.subject-display { padding: 12px 20px; background: #1a202c; }
.subject-display code { font-size: 14px; color: <?php echo $gold; ?>; }
.preview-frame { margin: 20px auto; max-width: 640px; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.3); }
.preview-label { padding: 10px 20px; background: #4a5568; font-size: 13px; color: #a0aec0; text-align: center; }
</style>
</head>
<body>

<div class="admin-bar">
    <h1>Email Preview — District 219 Candidate Announcement</h1>
    <?php if (d219_is_published()) : ?><span class="badge">ADMIN ONLY</span><?php else : ?><span class="badge" style="background:#38a169;">PRE-PUBLISH</span><?php endif; ?>
</div>

<!-- Subject Line -->
<div class="copy-section">
    <div class="copy-header">
        <h3>Subject Line</h3>
        <button class="copy-btn" onclick="copyText('subject-text', this)">Copy Subject</button>
    </div>
    <div class="subject-display">
        <code id="subject-text"><?php echo esc_html($subject_line); ?></code>
    </div>
</div>

<!-- HTML Code -->
<div class="copy-section">
    <div class="copy-header">
        <h3>Email HTML Code</h3>
        <button class="copy-btn" onclick="copyHtml(this)">Copy HTML</button>
    </div>
    <div class="copy-content">
        <code id="email-html-code"><?php echo esc_html($email_html); ?></code>
    </div>
</div>

<!-- Visual Preview -->
<div class="preview-frame">
    <div class="preview-label">Email Preview (how it will look in inbox)</div>
    <?php echo $email_html; ?>
</div>

<div style="height:40px;"></div>

<script>
function copyText(id, btn) {
    var text = document.getElementById(id).textContent;
    navigator.clipboard.writeText(text).then(function() {
        btn.textContent = 'Copied!';
        btn.classList.add('copied');
        setTimeout(function() { btn.textContent = 'Copy Subject'; btn.classList.remove('copied'); }, 2000);
    });
}
function copyHtml(btn) {
    var code = document.getElementById('email-html-code').textContent;
    navigator.clipboard.writeText(code).then(function() {
        btn.textContent = 'Copied!';
        btn.classList.add('copied');
        setTimeout(function() { btn.textContent = 'Copy HTML'; btn.classList.remove('copied'); }, 2000);
    });
}
</script>

</body>
</html>
