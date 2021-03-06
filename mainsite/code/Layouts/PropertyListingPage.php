<?php

class PropertyListingPage extends Page
{
    /**
     * Defines the allowed child page types
     * @var array
     */
    private static $allowed_children = array(
        'PropertyPage'
    );

    /**
     * Creating Permissions
     * @return boolean
     */
    public function canCreate($member = null)
    {
        if (PropertyListingPage::get()->count() > 0) {
            return false;
        }
        return true;
    }
}

class PropertyListingPage_Controller extends Page_Controller
{
    private static $url_handlers = array(
        '$region/$district/$suburb/$ID'    =>  'findProperty'
    );

    /**
     * Defines methods that can be called directly
     * @var array
     */
    private static $allowed_actions = array(
        'PropertySearchForm'
    );

    public function PropertySearchForm()
    {
        return new PropertySearchForm($this);
    }


    public function findProperty()
    {
        $request = $this->request;
        // Debugger::inspect($request);
    }

}
