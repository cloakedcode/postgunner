<?php

##
# Name of your blog/company/website.
#
# Used in email subjects/bodies.
#
# $config['SITE_NAME'] = "Singing the Blues";
##
$config['SITE_NAME'] = "Singing the Blues";

##
# Salt for subscription confirmation link hash.
#
# Make sure you generate a new salt for your installation.
# (e.g. `head -c 40 /dev/urandom | base64`)
#
# $config['HASH_SALT'] = "RANDSTRING";
##
$config['HASH_SALT'] = "9i16SrQkFoZVGXals6/YCO8nSTNbsGFpmuv0yqRqc/bYikLVvW5XmRGQ";

##
# Private Mailgun API key.
#
# Used for sending confirmation emails and
# adding subscribers to the mailing list.
#
# $config['PRIVATE_API_KEY'] = "key-048110f04dbd3d244f902j741644";
##
$config['PRIVATE_API_KEY'] = "key-049d8245b81230d3d2448b7174103644";

##
# Public Mailgun API key.
#
# Used for email address validation. If you leave it
# blank/empty validation will not be done.
#
# $config['PUBLIC_API_KEY'] = "pubkey-16edskj23091weoj22e544822502";
##
$config['PUBLIC_API_KEY'] = "pubkey-16edfa1fddd15a6d111882e544822502";

##
# Mailgun domain to send mail from.
#
# $config['DOMAIN'] = "mg.singingtheblues.com";
##
$config['DOMAIN'] = "sandboxd392bb1e4c4d474caec23997683919cb.mailgun.org";

##
# Mailgun mailinglist to subscribe users to.
#
# $config['MAILINGLIST'] = "posting@${config['DOMAIN']}";
##
$config['MAILINGLIST'] = "test@${config['DOMAIN']}";

##
# Email address to send mail from.
#
# To appease spam laws, this must be a valid email address.
#
# $config['FROM'] = "John Smith <john@singingtheblues.com>";
##
$config['FROM'] = "Alan Smith <alan@airpost.net>";

##
# Footer to include in emails.
#
# Can be HTML code or plain text.
#
# To appease spam laws, this must contain a valid street address.
#
# $config['FOOTER'] = "<br><p style='color: grey'>Location: 1060 West Addison Street Chicago, Illinois 60613</p>";
##
$config['FOOTER'] = "<br><p style='color: grey'>Location: 1060 West Addison Street Chicago, Illinois 60613</p>";

##
# Email subject to send with subscription confirmation.
#
# $config['CONFIRM_SUBJECT'] = "Confirm subscription to ${config['SITE_NAME']}";
##
$config['CONFIRM_SUBJECT'] = "Confirm subscription to ${config['SITE_NAME']}";

##
# Email body to send with subscription confirmation.
#
# Can be HTML code or plain text. All appearances of "LINK" will
# be replaced by the URL to confirm the subscription.
#
# If you don't want to hard code the body, you can grab a
# file's contents, or anything else you want in php. For example:
#
# $config['CONFIRM_BODY'] = file_get_contents("confirm_email.html");
#
# $config['CONFIRM_BODY'] = "<h1>${config['SITE_NAME']}</h1><p><a href='LINK'>Confirm your subscription to ${config['SITE_NAME']}.</a></p><p>If you cannot click the above link, copy and paste this URL into your browser:</p><p>LINK</p>";
##
$config['CONFIRM_BODY'] = "<h1>${config['SITE_NAME']}</h1><p><a href='LINK'>Confirm your subscription to ${config['SITE_NAME']}.</a></p><p>If you cannot click the above link, copy and paste this URL into your browser:</p><p>LINK</p>";
