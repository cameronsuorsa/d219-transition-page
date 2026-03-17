<?php
/**
 * Template: Email Preview for D219 Candidate Announcement
 * Admin-only — accessible at /staging/email even after publish date
 * Generates Constant Contact-ready HTML email with copy helpers
 */

if (!defined('ABSPATH')) exit;

$email_url = D219_ASSETS_URL . 'email/';
$roles = d219_get_candidates();
$bios = d219_get_candidate_bios();

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
        );
    }
    $role_groups[] = $group;
}

$subject_line = 'Meet Your District 219 Candidates — Election April 27th';

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
The nominated slate for District 219 leadership has been announced. See who&#8217;s running and learn about each candidate before the April 27th election.
</div>

<!-- Outer wrapper -->
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#f4f4f4;">
<tr><td align="center" style="padding:20px 10px;">

<!-- Email container 600px -->
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="600" style="max-width:600px;width:100%;background-color:#ffffff;border-radius:8px;overflow:hidden;">

<!-- ====== BANNER ====== -->
<tr>
<td style="padding:0;background-color:#772432;">
<a href="<?php echo esc_url($dlc_url); ?>" target="_blank" style="text-decoration:none;">
<img src="<?php echo esc_url($email_url . 'd219-banner.jpg'); ?>" alt="District 219 - Toastmasters International" width="600" style="display:block;width:100%;max-width:600px;height:auto;border:0;" />
</a>
</td>
</tr>

<!-- ====== GREETING ====== -->
<tr>
<td style="padding:30px 30px 10px;text-align:center;">
<h1 style="margin:0 0 12px;font-size:24px;font-weight:700;color:#1a365d;font-family:'Montserrat',Arial,Helvetica,sans-serif;">Meet Your District 219 Candidates</h1>
<p style="margin:0 0 6px;font-size:16px;color:#4a5568;line-height:1.5;font-family:'Source Sans Pro',Arial,Helvetica,sans-serif;">Hello [[First Name]],</p>
<p style="margin:0;font-size:16px;color:#4a5568;line-height:1.5;font-family:'Source Sans Pro',Arial,Helvetica,sans-serif;">The District Leadership Committee is pleased to announce the nominated slate of candidates for <strong>District 219</strong> leadership for the <strong>2026&ndash;2027</strong> Toastmasters year.</p>
</td>
</tr>

<!-- ====== DD MESSAGE ====== -->
<tr>
<td style="padding:16px 30px 10px;">
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#fefcf3;border-radius:8px;border:1px solid #ecc94b;">
<tr>
<td style="padding:18px 24px;">
<p style="margin:0 0 10px;font-size:15px;color:#2d3748;line-height:1.6;font-family:'Source Sans Pro',Arial,Helvetica,sans-serif;">As we prepare for the formation of District 219, we are excited to share the slate of candidates who have stepped forward to lead our new district. These individuals represent the best of Districts 10 and 13, and their willingness to serve reflects the spirit of collaboration that will define District 219.</p>
<p style="margin:0 0 10px;font-size:15px;color:#2d3748;line-height:1.6;font-family:'Source Sans Pro',Arial,Helvetica,sans-serif;">We encourage every member to review the candidate profiles, attend the Candidate Showcase, and participate in the election on April 27th. Your vote matters &mdash; together we will choose the leaders who will guide our district through its historic first year.</p>
<p style="margin:0;font-size:14px;color:#4a5568;font-style:italic;font-family:'Source Sans Pro',Arial,Helvetica,sans-serif;">&mdash; District Directors, Districts 10 &amp; 13</p>
</td>
</tr>
</table>
</td>
</tr>

<!-- ====== ELECTION CTA ====== -->
<tr>
<td style="padding:20px 30px;">
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#f0f5ff;border-radius:8px;border:1px solid #c3dafe;">
<tr>
<td style="padding:18px 24px;text-align:center;">
<p style="margin:0 0 4px;font-size:14px;color:#2b6cb0;font-weight:700;text-transform:uppercase;letter-spacing:1px;font-family:'Montserrat',Arial,Helvetica,sans-serif;">&#128499; District 219 Election Meeting</p>
<p style="margin:0 0 10px;font-size:18px;color:#1a365d;font-weight:700;font-family:'Montserrat',Arial,Helvetica,sans-serif;">April 27, 2026 &middot; 7:00 PM via Zoom</p>
<p style="margin:0;font-size:14px;color:#4a5568;font-family:'Source Sans Pro',Arial,Helvetica,sans-serif;">All dues-paid members of clubs within the District 219 boundary are eligible to vote.</p>
</td>
</tr>
</table>
</td>
</tr>

<!-- ====== CANDIDATE SORT NOTE ====== -->
<tr>
<td style="padding:10px 30px 0;text-align:center;">
<p style="margin:0;font-size:13px;color:#718096;font-style:italic;line-height:1.4;font-family:'Source Sans Pro',Arial,Helvetica,sans-serif;">Candidates are listed in alphabetical order by last name within each role. Roles with more than one candidate are marked as contested.</p>
</td>
</tr>

<!-- ====== CANDIDATE SECTIONS ====== -->
<?php foreach ($role_groups as $group) : ?>
<tr>
<td style="padding:10px 30px 0;">
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
<td style="padding:16px 0 8px;border-bottom:2px solid #004165;">
<h2 style="margin:0;font-size:18px;color:#004165;font-family:'Montserrat',Arial,Helvetica,sans-serif;font-weight:700;">
<?php echo esc_html($group['role']); ?>
<?php if ($group['region']) : ?><span style="font-weight:normal;font-size:14px;color:#718096;"> &mdash; <?php echo esc_html($group['region']); ?></span><?php endif; ?>
</h2>
<?php if ($group['contested']) : ?>
<p style="margin:4px 0 0;font-size:13px;color:#e53e3e;font-weight:bold;">&#9733; Contested &mdash; <?php echo count($group['candidates']); ?> candidates</p>
<?php endif; ?>
</td>
</tr>
</table>
</td>
</tr>

<?php foreach ($group['candidates'] as $c) : ?>
<tr>
<td style="padding:12px 30px;">
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
<td width="80" valign="top" style="padding-right:16px;">
<img src="<?php echo esc_url($c['photo_jpg']); ?>" alt="<?php echo esc_attr($c['name']); ?>" width="70" height="70" style="display:block;width:70px;height:70px;border-radius:50%;object-fit:cover;border:2px solid #e2e8f0;" />
</td>
<td valign="middle" style="font-size:15px;color:#2d3748;line-height:1.4;font-family:'Source Sans Pro',Arial,Helvetica,sans-serif;">
<strong style="font-size:16px;color:#1a365d;"><?php echo esc_html($c['name']); ?></strong><br />
<span style="font-size:13px;color:#718096;"><?php echo esc_html($c['credentials']); ?></span>
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
<td style="background-color:#004165;border-radius:6px;">
<a href="<?php echo esc_url($dlc_url); ?>" target="_blank" style="display:inline-block;padding:14px 36px;font-size:16px;font-weight:bold;color:#ffffff;text-decoration:none;font-family:'Source Sans Pro',Arial,Helvetica,sans-serif;">
&#128100; View Full Candidate Profiles
</a>
</td>
</tr>
</table>
<p style="margin:10px 0 0;font-size:13px;color:#a0aec0;">Read each candidate&rsquo;s full bio, compare responses side by side, and download their official PDFs.</p>
</td>
</tr>

<!-- ====== SHOWCASE VIDEOS ====== -->
<tr>
<td style="padding:20px 30px;">
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#fffaf0;border-radius:8px;border:1px solid #fbd38d;">
<tr>
<td style="padding:18px 24px;text-align:center;">
<p style="margin:0 0 6px;font-size:15px;color:#c05621;font-weight:700;font-family:'Montserrat',Arial,Helvetica,sans-serif;">&#127909; Candidate Showcase Videos Coming Soon</p>
<p style="margin:0;font-size:14px;color:#744210;line-height:1.5;">After <strong>April 22, 2026</strong>, recorded Candidate Showcase videos will be available on the <a href="<?php echo esc_url($dlc_url); ?>" target="_blank" style="color:#2b6cb0;text-decoration:underline;">candidate profiles page</a> so you can hear directly from each candidate before the election.</p>
</td>
</tr>
</table>
</td>
</tr>

<!-- ====== TRANSITION / ALIGNMENT ====== -->
<tr>
<td style="padding:10px 30px 20px;">
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#f0fff4;border-radius:8px;border:1px solid #c6f6d5;">
<tr>
<td style="padding:18px 24px;text-align:center;">
<p style="margin:0 0 6px;font-size:15px;color:#276749;font-weight:700;font-family:'Montserrat',Arial,Helvetica,sans-serif;">&#127758; District 219 Transition Updates</p>
<p style="margin:0 0 12px;font-size:14px;color:#2f855a;line-height:1.5;">District 10 and District 13 are merging to form <strong>District 219</strong> on July 1, 2026. The proposed club alignment into <strong>6 divisions and 27 areas</strong> is available to review, along with the timeline and transition committee details.</p>
<table role="presentation" cellpadding="0" cellspacing="0" border="0" align="center">
<tr>
<td style="background-color:#276749;border-radius:6px;">
<a href="<?php echo esc_url($transition_url); ?>" target="_blank" style="display:inline-block;padding:10px 24px;font-size:14px;font-weight:bold;color:#ffffff;text-decoration:none;font-family:'Source Sans Pro',Arial,Helvetica,sans-serif;">
&#9432; View Transition Details &amp; Club Alignment
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
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#faf5ff;border-radius:8px;border:1px solid #d6bcfa;">
<tr>
<td style="padding:18px 24px;text-align:center;">
<p style="margin:0 0 6px;font-size:15px;color:#553c9a;font-weight:700;font-family:'Montserrat',Arial,Helvetica,sans-serif;">&#128588; Interested in Serving District 219?</p>
<p style="margin:0 0 12px;font-size:14px;color:#6b46c1;line-height:1.5;">While elected positions are on the ballot, there are many <strong>appointed and volunteer roles</strong> available &mdash; Area Directors, Finance Manager, PR Chair, Conference Chair, and more.</p>
<table role="presentation" cellpadding="0" cellspacing="0" border="0" align="center">
<tr>
<td style="background-color:#553c9a;border-radius:6px;">
<a href="https://docs.google.com/forms/d/e/1FAIpQLScVoaKQ8Sq8Yp_mTAwsHnahVUAjr9qXdlOV0wvzzdh9f6L-sQ/viewform" target="_blank" style="display:inline-block;padding:10px 24px;font-size:14px;font-weight:bold;color:#ffffff;text-decoration:none;font-family:'Source Sans Pro',Arial,Helvetica,sans-serif;">
&#9997;&#65039; Quick Interest Form
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
<td style="padding:20px 30px;background-color:#1a365d;text-align:center;border-radius:0 0 8px 8px;">
<p style="margin:0 0 8px;font-size:14px;color:#ffffff;font-weight:700;font-family:'Montserrat',Arial,Helvetica,sans-serif;">District 219 Toastmasters</p>
<p style="margin:0 0 12px;font-size:13px;color:#a0aec0;line-height:1.5;">
<a href="<?php echo esc_url($transition_url); ?>" target="_blank" style="color:#90cdf4;text-decoration:underline;">Transition Overview</a> &nbsp;&middot;&nbsp;
<a href="<?php echo esc_url($dlc_url); ?>" target="_blank" style="color:#90cdf4;text-decoration:underline;">Meet the Candidates</a>
</p>
<p style="margin:0;font-size:11px;color:#718096;line-height:1.4;">
The information in this email is for the sole use of Toastmasters&rsquo; members, for Toastmasters business only.<br />
It is not to be used for solicitation and distribution of non-Toastmasters material or information.
</p>
</td>
</tr>

</table>
<!-- /Email container -->

<!-- Constant Contact unsubscribe -->
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="600" style="max-width:600px;width:100%;">
<tr>
<td style="padding:16px 30px;text-align:center;">
<p style="margin:0;font-size:11px;color:#a0aec0;line-height:1.4;">
[[trackingImage]]<br />
<a href="[[viewAsWebpage]]" style="color:#718096;text-decoration:underline;">View as webpage</a><br />
<a href="[[unsubscribeUrl]]" style="color:#718096;text-decoration:underline;">Unsubscribe</a> | <a href="[[managePreferencesUrl]]" style="color:#718096;text-decoration:underline;">Manage Preferences</a><br />
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
.admin-bar { background: #2d3748; padding: 16px 24px; display: flex; align-items: center; gap: 16px; flex-wrap: wrap; border-bottom: 3px solid #4299e1; }
.admin-bar h1 { font-size: 18px; color: #fff; flex: 1; }
.admin-bar .badge { background: #e53e3e; color: #fff; font-size: 11px; padding: 3px 8px; border-radius: 4px; font-weight: 600; }
.copy-section { background: #2d3748; margin: 20px auto; max-width: 640px; border-radius: 8px; overflow: hidden; }
.copy-header { padding: 12px 20px; background: #4a5568; display: flex; align-items: center; justify-content: space-between; }
.copy-header h3 { font-size: 14px; color: #e2e8f0; }
.copy-btn { background: #4299e1; color: #fff; border: none; padding: 6px 16px; border-radius: 4px; cursor: pointer; font-size: 13px; font-weight: 600; }
.copy-btn:hover { background: #3182ce; }
.copy-btn.copied { background: #48bb78; }
.copy-content { padding: 12px 20px; background: #1a202c; max-height: 120px; overflow-y: auto; }
.copy-content code { font-size: 12px; color: #a0aec0; word-break: break-all; white-space: pre-wrap; }
.subject-display { padding: 12px 20px; background: #1a202c; }
.subject-display code { font-size: 14px; color: #f6e05e; }
.preview-frame { margin: 20px auto; max-width: 640px; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.3); }
.preview-label { padding: 10px 20px; background: #4a5568; font-size: 13px; color: #a0aec0; text-align: center; }
.note { background: #2d3748; margin: 20px auto; max-width: 640px; padding: 16px 20px; border-radius: 8px; border-left: 4px solid #f6ad55; }
.note h4 { color: #f6ad55; margin-bottom: 6px; font-size: 14px; }
.note p, .note li { font-size: 13px; color: #cbd5e0; line-height: 1.6; }
.note ul { padding-left: 20px; margin-top: 6px; }
</style>
</head>
<body>

<div class="admin-bar">
    <h1>&#9993; Email Preview — District 219 Candidate Announcement</h1>
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

<!-- Notes -->
<div class="note">
    <h4>&#9888;&#65039; Before Sending This Email</h4>
    <ul>
        <li><strong>Banner image:</strong> Save your D219 banner as <code>assets/email/d219-banner.jpg</code> (PNG also works). Must be JPG or PNG — webp is not supported in most email clients.</li>
        <li><strong>Candidate photos:</strong> JPG versions (150px) are already generated in <code>assets/email/</code>.</li>
        <li><strong>Constant Contact variables:</strong> <code>[[First Name]]</code>, <code>[[unsubscribeUrl]]</code>, <code>[[viewAsWebpage]]</code>, <code>[[managePreferencesUrl]]</code>, <code>[[organizationAddress]]</code>, <code>[[trackingImage]]</code> are included.</li>
        <li><strong>Image hosting:</strong> All images reference <code><?php echo esc_html($email_url); ?></code> — they must be publicly accessible on the live site.</li>
        <li><strong>Links:</strong> All links point to live URLs (<code>/transition</code> and <code>/dlc</code>), not staging.</li>
        <li><strong>DD message:</strong> The intro message from the District Directors is a shared draft. Both DDs should review and approve the final wording before send.</li>
        <li><strong>Fonts:</strong> Uses Toastmasters brand fonts (Source Sans Pro for body, Montserrat for headings) via Google Fonts. Falls back to Arial/Helvetica in Outlook and clients that strip web fonts.</li>
    </ul>
</div>

<div class="note">
    <h4>&#128203; Waiting On (Page Publish Blockers)</h4>
    <ul>
        <li>Bio PDF for Jolyn Redic (wasn't included in her Google Drive folder)</li>
        <li>Official DLC Nomination Report PDF</li>
        <li>Candidate Showcase Videos (available after April 22, 2026)</li>
        <li>Verify Quick Interest Form — remove elected position options</li>
        <li>Melissa / transition committee page review &amp; feedback</li>
        <li>Set <code>D219_DLC_MODE</code> to <code>'candidates'</code></li>
        <li>Set <code>D219_PUBLISH_DATE</code> to coordinated release date/time</li>
        <li>Coordinate release timing with D10 (Tricia) and newsletters</li>
        <li>Remove "NOT READY FOR D10 UPDATE" from release notes</li>
    </ul>
</div>

<!-- Visual Preview -->
<div class="preview-frame">
    <div class="preview-label">&#128065; Email Preview (how it will look in inbox)</div>
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
