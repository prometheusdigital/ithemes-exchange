<?php
/**
 * Contains the sendable interface.
 *
 * @since   1.36
 * @license GPLv2
 */

/**
 * Class IT_Exchange_Email
 */
interface IT_Exchange_Sendable {

	/**
	 * Get the subject line.
	 *
	 * @since 1.36
	 *
	 * @return string
	 */
	public function get_subject();

	/**
	 * Get the body.
	 *
	 * @since 1.36
	 *
	 * @return string
	 */
	public function get_body();

	/**
	 * Get the email template.
	 *
	 * @since 1.36
	 *
	 * @return IT_Exchange_Email_Template
	 */
	public function get_template();

	/**
	 * Get the recipient for this email.
	 *
	 * @since 1.36
	 *
	 * @return IT_Exchange_Email_Recipient
	 */
	public function get_recipient();

	/**
	 * Get the CCs for this email.
	 *
	 * @since 1.36
	 *
	 * @return IT_Exchange_Email_Recipient[]
	 */
	public function get_ccs();

	/**
	 * Get the BCCs for this email.
	 *
	 * @since 1.36
	 *
	 * @return IT_Exchange_Email_Recipient[]
	 */
	public function get_bccs();

	/**
	 * Get the context for this email.
	 *
	 * @since 1.36
	 *
	 * @return array
	 */
	public function get_context();
}