<?php
/**
 * @author         Pierre-Henry Soria <ph7software@gmail.com>
 * @copyright      (c) 2012-2017, Pierre-Henry Soria. All Rights Reserved.
 * @license        GNU General Public License; See PH7.LICENSE.txt and PH7.COPYRIGHT.txt in the root directory.
 * @package        PH7 / App / System / Module / User / Controller
 */
namespace PH7;

use
PH7\Framework\Navigation\Page,
PH7\Framework\Mvc\Router\Uri,
PH7\Framework\Url\Header;

class BrowseController extends Controller
{
    const MAX_PROFILE_PER_PAGE = 24;

    private $oUserModel, $oPage, $iTotalUsers;

    public function __construct()
    {
        parent::__construct();

        $this->oUserModel = new UserModel;
        $this->oPage = new Page;
    }

    public function index()
    {
        $this->iTotalUsers = $this->oUserModel->search($_GET, true, null, null);
        $this->view->total_pages = $this->oPage->getTotalPages($this->iTotalUsers, self::MAX_PROFILE_PER_PAGE);
        $this->view->current_page = $this->oPage->getCurrentPage();
        $oUsers = $this->oUserModel->search($_GET, false, $this->oPage->getFirstItem(), $this->oPage->getNbItemsByPage());

        if (empty($oUsers))
        {
            Header::redirect(Uri::get('user', 'search', 'index'), t('Empty search result. Please try again with wider or new search parameters.'));
        }
        else
        {
            // We can put HTML tags in the <title> tag as our template engine will remove all HTML tags present in the title tag, ...
            $this->view->page_title = t('Browse Members');
            $this->view->h1_title = '<span class="pH1">' . t('Browse Members') . '</span>';
            $this->view->h3_title = t('Meet new People with %0%', '<span class="pH0">' . $this->registry->site_name . '</span>');
            $this->view->meta_description = t('Meet new People and Friends near you with %site_name% - Browse Members');
            $this->view->avatarDesign = new AvatarDesignCore;
            $this->view->users = $oUsers;
            $this->output();
        }
    }
}
