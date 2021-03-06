<?php
/**
 * An email sent to the user with a link to validate and activate their account.
 *
 */
class AppointmentConfirmation extends Email
{
	public function __construct($appointment, $isNew, $state = false) {
		$from     =    Config::inst()->get('Email', 'noreply_email');
		$to       =    $appointment->Client()->Email . ',' . $appointment->Business()->BusinessOwner()->Email;
		$subject  =    '[' . SiteConfig::current_site_config()->Title . '] Service Appointment ' . ($state ? strtolower($state) : ($isNew ? 'confirmation' : 'update notification'));

		parent::__construct($from, $to, $subject);

		$this->setTemplate('AppointmentConfirmation');

        $this->populateTemplate(new ArrayData(
            array (
                'baseURL'   =>  Director::absoluteURL(Director::baseURL()),
                'isNew'     =>  $isNew,
                'State'     =>  strtolower($state),
                'Business'  =>  $appointment->Business()->Title,
                'Client'    =>  $appointment->Client()->getDisplayName(),
                'Venue'     =>  $appointment->Client()->FullAddress,
                'Time'      =>  $appointment->Date,
                'Memo'      =>  $appointment->Memo,
            )
        ));
	}
}
