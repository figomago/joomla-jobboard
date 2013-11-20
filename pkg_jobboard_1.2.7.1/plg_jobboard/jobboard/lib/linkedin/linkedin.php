<?php

/**
 * This file is used in conjunction with the 'LinkedIn' class, demonstrating 
 * the basic functionality and usage of the library.
 *
 * COPYRIGHT:
 *   
 * Copyright (C) 2011, fiftyMission Inc.
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a
 * copy of this software and associated documentation files (the "Software"), 
 * to deal in the Software without restriction, including without limitation 
 * the rights to use, copy, modify, merge, publish, distribute, sublicense, 
 * and/or sell copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in 
 * all copies or substantial portions of the Software.  
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR 
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, 
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE 
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER 
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING 
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS 
 * IN THE SOFTWARE.  
 *
 * SOURCE CODE LOCATION:
 * 
 *   http://code.google.com/p/simple-linkedinphp/
 *    
 * REQUIREMENTS:
 *
 * 1. You must have cURL installed on the server and available to PHP.
 * 2. You must be running PHP 5+.  
 *  
 * QUICK START:
 * 
 * There are two files needed to enable LinkedIn API functionality from PHP; the
 * stand-alone OAuth library, and the Simple-LinkedIn library. The latest 
 * version of the stand-alone OAuth library can be found on Google Code:
 * 
 *   http://code.google.com/p/oauth/
 * 
 * The latest versions of the Simple-LinkedIn library and this demonstation 
 * script can be found here:
 * 
 *   http://code.google.com/p/simple-linkedinphp/
 *
 * Install these two files on your server in a location that is accessible to 
 * this demo script. Make sure to change the file permissions such that your 
 * web server can read the files.
 * 
 * Next, make sure the path to the LinkedIn class below is correct.
 * 
 * Finally, read and follow the 'Quick Start' guidelines located in the comments
 * of the Simple-LinkedIn library file.   
 *
 * @version 3.1.1 - July 12, 2011
 * @author Paul Mennega <paul@fiftymission.net>
 * @copyright Copyright 2011, fiftyMission Inc. 
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License 
 */
 ?>
<?php
/**
 * @package JobBoard
 * @copyright Copyright (c)2010-2013 figomago <http://figomago.wordpress.com>
 * @license GNU General Public License version 3, or later
 */

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');


class LinkedinHelper
{

  function __construct()
	{

	}

    /**
     * Session check.
     *
     * Helper function that checks to see that we have a 'set' session variable that we can
     * use.
     */
    function oauth_session_exists() {
      $session =& JFactory::getSession();
      $oauth_insession = ($session->get('oauth'))? true : false;
        if(!$oauth_insession){
           $session_oauth = array();
           $session->set('oauth', $session_oauth);
        }
      return $session;
    }

    function getLinkedinProfile($req_type) {
        $session = LinkedinHelper::oauth_session_exists();
        $app = JFactory::getApplication();
        // include the LinkedIn class
        require_once('linkedin_3.1.1.class.php');


        // display constants
        $API_CONFIG = array(
          'appKey'       => 'r7xckk3gxdfg',
      	  'appSecret'    => 'fJQh57Ex2Qg7YyPi',
      	  'callbackUrl'  => NULL
        );
        define('CONNECTION_COUNT', 20);
        define('PORT_HTTP', '80');
        define('PORT_HTTP_SSL', '443');
        define('UPDATE_COUNT', 10);

        // set index
        //$_REQUEST[LINKEDIN::_GET_TYPE] = (isset($_REQUEST[LINKEDIN::_GET_TYPE])) ? $_REQUEST[LINKEDIN::_GET_TYPE] : '';
        switch($req_type) {
          case 'initiate':
            /**
             * Handle user initiated LinkedIn connection, create the LinkedIn object.
             */

            // check for the correct http protocol (i.e. is this script being served via http or https)
           if(isset($_SERVER['HTTPS'])) {
              if($_SERVER['HTTPS'] == 'on') {
                $protocol = 'https';
              } else {
                $protocol = 'http';
              }
           } else {
                $protocol = 'http';
              }

            // set the callback url
            $API_CONFIG['callbackUrl'] = $protocol . '://' . $_SERVER['SERVER_NAME'] . ((($_SERVER['SERVER_PORT'] != PORT_HTTP) || ($_SERVER['SERVER_PORT'] != PORT_HTTP_SSL)) ? ':' . $_SERVER['SERVER_PORT'] : '') . $_SERVER['PHP_SELF'] . '?' . $req_type . '=initiate&' . LINKEDIN::_GET_RESPONSE . '=1';
            // $API_CONFIG['callbackUrl'] = JRoute::_('index.php?option=com_jobboard&view=user') . '&' . $req_type . '=initiate&' . LINKEDIN::_GET_RESPONSE . '=1';

           //  echo $API_CONFIG['callbackUrl'];die;

            $OBJ_linkedin = new LinkedIn($API_CONFIG);

            // check for response from LinkedIn
            $_GET[LINKEDIN::_GET_RESPONSE] = (isset($_GET[LINKEDIN::_GET_RESPONSE])) ? $_GET[LINKEDIN::_GET_RESPONSE] : '';
            if(!$_GET[LINKEDIN::_GET_RESPONSE]) {
              // LinkedIn hasn't sent us a response, the user is initiating the connection

              // send a request for a LinkedIn access token
              $response = $OBJ_linkedin->retrieveTokenRequest();
              //echo 'response: '.'<pre>'.print_r($response, true).'</pre>';
              if($response['success'] === TRUE) {
                // store the request token
                $session_oauth = $session->get('oauth');
                $session_oauth['oauth']['linkedin']['request'] = $response['linkedin'];
                $session->set('oauth', $session_oauth);

                //redirect the user to the LinkedIn authentication/authorisation page to initiate validation.            
                $app->redirect(LINKEDIN::_URL_AUTH . $response['linkedin']['oauth_token']);

              } else {
                    $profile_assoc = array();
                    $profile_assoc['response'] = false;
                    $profile_assoc['msg'] = "Request token retrieval failed:<br /><br />RESPONSE:<br /><br /><pre>";
                    return $profile_assoc;
                // bad token request
              }
            } else { // LinkedIn has sent a response

              // user has cancelled LinkedIn authentication
              if(JRequest::getString('oauth_problem') == 'user_refused') {
                 $app->redirect('index.php?option=com_jobboard&view=user&task=addcv', JText::_('COM_JOBBOARD_IMPORTLINKEDINCANCELLED'));
              }

              // user has granted permission, take the temp access token, the user's secret and the verifier to request the user's real secret key
              $session_oauth = $session->get('oauth');
              $response = $OBJ_linkedin->retrieveTokenAccess($session_oauth['oauth']['linkedin']['request']['oauth_token'], $session_oauth['oauth']['linkedin']['request']['oauth_token_secret'], $_GET['oauth_verifier']);
              if($response['success'] === TRUE) {
                // the request went through without an error, gather user's 'access' tokens
                $session_oauth['oauth']['linkedin']['access'] = $response['linkedin'];

                // set the user as authorized for future quick reference
                $session_oauth['oauth']['linkedin']['authorized'] = TRUE;
                $session->set('oauth', $session_oauth);

              } else {
                // bad token access
                echo "Access token retrieval failed:<br /><br />RESPONSE:<br /><br /><pre>" . print_r($response, TRUE) . "</pre><br /><br />LINKEDIN OBJ:<br /><br /><pre>" . print_r($OBJ_linkedin, TRUE) . "</pre>";
              }
            }
            $response = $OBJ_linkedin->profile('~:(id,first-name,last-name,summary,educations,positions,skills)');
                  if($response['success'] === TRUE) {                                        
                  $profile = new SimpleXMLElement($response['linkedin']);
                  $profile_json =  json_encode($profile);
                  $profile_assoc = array_unique(json_decode($profile_json, TRUE), SORT_REGULAR);
                  $profile_assoc['response'] = $response['success'];
                  } else {
                    // profile retrieval failed
                    $profile_assoc = array();
                    $profile_assoc['response'] = false;
                  }
                  
              return $profile_assoc;
            break;

          case 'revoke':
            /**
             * Handle authorization revocation.
             */

            $session_oauth = $session->get('oauth');
            $OBJ_linkedin = new LinkedIn($API_CONFIG);
            $OBJ_linkedin->setTokenAccess($session_oauth['oauth']['linkedin']['access']);
            $response = $OBJ_linkedin->revoke();
            if($response['success'] === TRUE) {
              // revocation successful, clear session
              if($session->clear('oauth')) {
                // session destroyed
                //header('Location: ' . $_SERVER['PHP_SELF']);
              } else {
                // session not destroyed
                echo "Error clearing user's session";
              }
            } else {
              // revocation failed
              echo "Error revoking user's token:<br /><br />RESPONSE:<br /><br /><pre>" . print_r($response, TRUE) . "</pre><br /><br />LINKEDIN OBJ:<br /><br /><pre>" . print_r($OBJ_linkedin, TRUE) . "</pre>";
            }
            break;
          default:
            // nothing being passed back, display demo page

            // check PHP version
            if(version_compare(PHP_VERSION, '5.0.0', '<')) {
              throw new LinkedInException('You must be running version 5.x or greater of PHP to use this library.');
            }

            // check for cURL
            if(extension_loaded('curl')) {
              $curl_version = curl_version();
              $curl_version = $curl_version['version'];
            } else {
              throw new LinkedInException('You must load the cURL extension to use this library.');
            }
                $session_oauth = $session->get('oauth');
                if (!isset($session_oauth['oauth']['linkedin']['authorized'])) {
                   $session_oauth['oauth']['linkedin']['authorized'] = FALSE;
                   $session->set('oauth', $session_oauth);
                }
                if($session_oauth['oauth']['linkedin']['authorized'] === TRUE) {
                  // user is already connected
                  $OBJ_linkedin = new LinkedIn($API_CONFIG);
                  $OBJ_linkedin->setTokenAccess($session_oauth['oauth']['linkedin']['access']);
                  ?>

                  <?php
                  //$response = $OBJ_linkedin->profile('~:(id,first-name,last-name,picture-url)');
                  $response = $OBJ_linkedin->profile('~:(id,first-name,last-name,summary,educations,positions,skills)');
                  if($response['success'] === TRUE) {
                    //$response['linkedin'] = new SimpleXMLElement($response['linkedin']);
                    // echo "<pre>" . print_r($response['linkedin'], TRUE) . "</pre>";

                  $profile = new SimpleXMLElement($response['linkedin']);
                  $profile_json = json_encode($profile);
                  return json_decode($profile_json,TRUE);
                  } else {
                    // profile retrieval failed
                    echo "Error retrieving profile information:<br /><br />RESPONSE:<br /><br /><pre>" . print_r($response) . "</pre>";
                  }
                } else {
                  // user isn't connected
                  ?>
                  <?php
                }
                ?>
            <?php
              break;
          }
      }

      function doOauth($url) {
          $app = JFactory::getApplication();

          $app->redirect($url);
      }
}

?>