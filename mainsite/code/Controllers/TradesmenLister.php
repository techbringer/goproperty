<?php
use SaltedHerring\Debugger;

class TradesmenLister extends Page_Controller
{
    public function index($request)
    {
        if ($serviceSlug = $request->getVar('WorkType')) {
            $service = Service::get()->filter(array('Slug' => $serviceSlug))->first();
            $business = $service->Business();
        } else {
            $business = Business::get();
        }

        $filters = array();
        if ($region = $request->param('region')) {
            $filters['RegionSlug'] = $region;
        }

        if ($district = $request->param('district')) {
            $filters['CitySlug'] = $district;
        }

        if ($suburb = $request->param('suburb')) {
            $filters['SuburbSlug'] = $suburb;
        }

        if ($slug = $request->param('slug')) {
            $filters['Slug'] = $slug;
        }

        $business = $business->filter($filters);

        if (!empty($slug)) {
            $business = $business->first();
        } else {
            $business = new PaginatedList($business, $request);
            $business->setPageLength(12);
        }

        return $this->customise(array('Business' => $business))->renderWith(array(!empty($slug) ? 'BusinessPage' : 'TradesmenList', 'Page'));
    }

    public function getTitle()
    {
        $segments = $_GET['url'];
        $segments = explode('/', $segments);
        $region = $district = $suburb = '';
        if (count($segments) > 2) {
            $region = ' › ' . $segments[2];
        }

        if (count($segments) > 3) {
            $district = ' › ' . $segments[3];
        }

        if (count($segments) > 4) {
            $suburb = ' › ' . $segments[4];
        }

        return 'Tradesmen' . $region . $district . $suburb;
    }

    public function Title()
    {
        return $this->getTitle();
    }
}
