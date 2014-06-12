survey_randomizer
=================

Task
----

We have a set of survey questions that we require a fixed number of answers for each.
Due to technical limitations of the survey host we cannot set up suitable randomisation there.
One solution has been proposed which is to set up a number of different surveys.
This can be set up so that each survey needs to be completed only once.
The question now is how to direct users from a single entry page to the surveys so that each one is completed once.

Solution
--------

Implement PHP script which 

* Allows an admin to edit the list of URLs.
* Randomly redirects a user to one of the URLs and removes it from the list.

Some implementation detalis
---------------------------

* Admin should know a secret token to have an access to the list editor.
* When list is empty, user should be redirected to the special page.

* URL for editing URLs list: http://dyatlov.net/sites/survey_randomizer/?pass=3JoeLHVpzmZ24PvHwBUzxr6wLWB1P8D5
* URL for redirect: http://dyatlov.net/sites/survey_randomizer

