<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Codeigniter Piwik Stat Example Controller
 *
 * Example Functions for dashboard displaying of Piwik Stats
 *
 * @author        Bryce Johnston < bryce@wingdspur.com >
 * @license       MIT
 */

class PiwikStats extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->library('piwik');
    }
    
    // Get unique, visits stats to display in a flot graph.
    public function index()
    {
        $visits = $this->piwik->actions('day', 'today');
        $unique = $this->piwik->unique_visitors(array('day', 10), 3600);
        // Recommend using sometype of caching for this, example:
        // $visits = $this->cache->library('piwik', 'actions', array('day', 10), 3600);
        // $unique = $this->cache->library('piwik', 'unique_visitors', array('day', 10), 3600);
        
        foreach($visits as $date => $visit)
        { 
            $date_arr = explode('-', $date);
            $year = $date_arr[0];
            $month = $date_arr[1];
            $day = $date_arr[2];

            $utc = mktime(date('h') + 1, NULL, NULL, $month, $day, $year) * 1000;

            $flot_visits[] = '[' . $utc . ',' . $visit . ']';
            $flot_unique[] = '[' . $utc . ',' . $unique[$date] . ']';
        }

        $data['visits'] = '[' . implode(',', $flot_visits) . ']';
        $data['unique'] = '[' . implode(',', $flot_unique) . ']';
        $this->load->view('index', $data);
    }

    public function top_pages()
    {
        $data['page_titles'] = $this->cache->library('piwik', 'page_titles', array('day', 'today'), 3600);
        $this->load->view('top_pages', $data);
    }

    public function last_visitors()
    {
        $data['last_visits'] = $this->cache->library('piwik', 'last_visits_parsed', array('today', 20), 3600);
        $this->load->view('last_visitors', $data);
    }

    public function downloads()
    {
        $data['downloads'] = $this->cache->library('piwik', 'downloads', array('day', 10), 3600);
        $this->load->view('downloads', $data);
    }

    public function outlinks()
    {
        $data['outlinks'] = $this->cache->library('piwik', 'outlinks', array('day', 10), 3600);
        $this->load->view('outlinks', $data);
    }

    public function keywords()
    {
        $data['keywords'] = $this->cache->library('piwik', 'keywords', array('day', 'today'), 3600);
        $this->load->view('keywords', $data);
    }

    public function refering_sites()
    {
        $data['websites'] = $this->cache->library('piwik', 'websites', array('day', 'today'), 3600);
        $this->load->view('refering_sites', $data);
    }

    public function search_engines()
    {
        $data['search_engines'] = $this->cache->library('piwik', 'search_engines', array('day', 10), 3600);
        $this->load->view('search_engines', $data);
    }
    

}

/* End of file piwik_stats.php */
