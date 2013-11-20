<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com> <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');

class JobBoardToolbarHelper
{
    static function setToolbarLinks($item) {

		$dashboard = 'index.php?option=com_jobboard&view=dashboard';
		$jobs = 'index.php?option=com_jobboard&view=jobs';
		$applicants = 'index.php?option=com_jobboard&view=applicants';
		$messages = 'index.php?option=com_jobboard&view=messages';
		$category = 'index.php?option=com_jobboard&view=category';
		$careerlevels = 'index.php?option=com_jobboard&view=careerlevels';
		$education = 'index.php?option=com_jobboard&view=education';
		$departments = 'index.php?option=com_jobboard&view=departments';
        $statuses = 'index.php?option=com_jobboard&view=statuses';
        $config = 'index.php?option=com_jobboard&view=config';

		// add sub menu items
		JSubMenuHelper::addEntry(JText::_('COM_JOBBOARD_M_DASHBOARD'), $dashboard, ($item == 'dashboard')? true : false);
		JSubMenuHelper::addEntry(JText::_('COM_JOBBOARD_M_JOBS'), $jobs, ($item == 'jobs')? true : false);
		JSubMenuHelper::addEntry(JText::_('COM_JOBBOARD_JOB_APPLICANTS'), $applicants, ($item == 'applicants')? true : false);
		JSubMenuHelper::addEntry(JText::_('COM_JOBBOARD_EMAIL_TEMPLATES'), $messages, ($item == 'messages')? true : false);
		JSubMenuHelper::addEntry(JText::_('COM_JOBBOARD_JOB_CATEGORIES'), $category, ($item == 'category')? true : false);
		JSubMenuHelper::addEntry(JText::_('COM_JOBBOARD_CAREER_LEVELS'), $careerlevels, ($item == 'careerlevels')? true : false);
		JSubMenuHelper::addEntry(JText::_('COM_JOBBOARD_EDUCATION_LEVELS'), $education, ($item == 'education')? true : false);
		JSubMenuHelper::addEntry(JText::_('COM_JOBBOARD_DEPARTMENTS'), $departments, ($item == 'departments')? true : false);
		JSubMenuHelper::addEntry(JText::_('COM_JOBBOARD_STATUSES'), $statuses, ($item == 'statuses')? true : false);   
        JSubMenuHelper::addEntry(JText::_('COM_JOBBOARD_SETTINGS'), $config, ($item == 'config')? true : false);
    }
}

?>