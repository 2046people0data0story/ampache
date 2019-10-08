<?php
/* vim:set tabstop=8 softtabstop=8 shiftwidth=8 noexpandtab: */
/**
 * LostPassword
 *
 *
 * LICENSE: GNU General Public License, version 2 (GPLv2)
 * Copyright (c) 2001 - 2011 Ampache.org All Rights Reserved
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License v2
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 * @package	Ampache
 * @copyright	2001 - 2011 Ampache.org
 * @license	http://opensource.org/licenses/gpl-2.0 GPLv2
 * @link	http://www.ampache.org/
 */

define('NO_SESSION','1');
require_once 'lib/init.php';

$action = (isset($_POST['action'])) ? $_POST['action'] : "";

switch ($action) {
	case 'send':
		/* Check for posted email */
		$result = false;
		if (isset($_POST['email']) && $_POST['email']) {
			/* Get the email address and the current ip*/
			$email = scrub_in($_POST['email']);
			$current_ip =(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] :$_SERVER['REMOTE_ADDR'];
			$result = send_newpassword($email, $current_ip);
		}
		/* Do not acknowledge a password has been sent or failed
		if ($result) {
			AmpError::add('general', T_('Password has been sent'));
		} else {
			AmpError::add('general', T_('Password has not been sent'));
		}
		}*/
		require Config::get('prefix') . '/templates/show_login_form.inc.php';
		break;
	default:
		require Config::get('prefix') . '/templates/show_lostpassword_form.inc.php';
}

function send_newpassword($email,$current_ip){
	/* get the Client and set the new password */
	$client = User::get_from_email($email);

	if (!client) {
		return false;
	}
	if ($client->has_access(100)) {
		return false;
	}

	if ($client->email == $email) {
		$newpassword = generate_password();
		$client->update_password($newpassword);

		$mailer = new AmpacheMail();
		$mailer->set_default_sender();
		$mailer->subject = T_("Lost Password");
		$mailer->recipient_name = $client->fullname;
		$mailer->recipient = $client->email;

		$message  = sprintf(T_("A user from %s has requested a password reset for '%s'."), $current_ip, $client->username);
		$message .= "\n";
		$message .= sprintf(T_("The password has been set to: %s"), $newpassword);
		$mailer->message = $message;

		return $mailer->send();
	}
	return false;
}
?>
