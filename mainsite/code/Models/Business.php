<?php
use Cocur\Slugify\Slugify;

class Business extends DataObject
{
    /**
     * Database fields
     * @var array
     */
    private static $db = array(
        'Title'         =>  'Varchar(256)',
        'ContactNumber' =>  'Varchar(24)',
        'Content'       =>  'Text'
    );

    private static $extensions = array(
        'AddressProperties',
        'SlugExtension'
    );

    /**
     * Belongs_to relationship
     * @var array
     */
    private static $belongs_to = array(
        'Member'        =>  'Member'
    );


    /**
     * Has_one relationship
     * @var array
     */
    private static $has_one = array(
        'Logo'          =>  'Image',
        'BusinessOwner' =>  'Member'
    );

    /**
     * Many_many relationship
     * @var array
     */
    private static $many_many = array(
        'Services'  =>  'Service'
    );

    /**
     * CMS Fields
     * @return FieldList
     */
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->addFieldsToTab(
            'Root.Location',
            array(
                TextField::create('FullAddress', 'Address'),
                TextField::create('UnitNumber', 'Unit/Room/Apartment/Flat number'),
                TextField::create('StreetNumber', 'Street number'),
                TextField::create('StreetName', 'Street'),
                TextField::create('Suburb', 'Suburb')->setDescription('slug: ' . $this->SuburbSlug),
                TextField::create('City', 'City')->setDescription('slug: ' . $this->CitySlug),
                TextField::create('Region', 'Region')->setDescription('slug: ' . $this->RegionSlug),
                TextField::create('Country', 'Country'),
                TextField::create('PostCode', 'Post code'),
                TextField::create('Lat', 'Latitude'),
                TextField::create('Lng', 'Longitude')
            )
        );
        return $fields;
    }

    public function onBeforeWrite()
    {
        parent::onBeforeWrite();
        if (empty($this->ID)) {
            $this->BusinessOwnerID = Member::currentUserID();
        }
        if (!empty($this->Content)) {
            $this->Content = strip_tags($this->Content);
        }
    }

    public function Friendlify($string)
    {
        if (!empty($string)) {
            $string = str_replace("\n", '<br />', $string);
        }
        return $string;
    }

    public function Link()
    {
        $slugify = new Slugify();
        return Director::baseURL() . 'tradesmen/' . (!empty($this->Region) ? $slugify->slugify($this->Region) . '/' : '') . (!empty($this->City) ? $slugify->slugify($this->City) . '/' : '') . (!empty($this->Suburb) ? $slugify->slugify($this->Suburb) . '/' : '') . $this->Slug;
    }
}
