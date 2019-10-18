***************************
******* Communicator *********
Simple Newsletter Component
***************************

License: 
-------------------------
GNU / GPL(see LICENSE.txt)

Authors: 
-------------------------
* Roland Dalmulder <contact@csvimproved.com>
    (updated to Joomla 1.5 Native for www.granholmcms.com)
* Soeren Eberhardt <soeren@virtuemart.net>
    (who just needed an easy and *working* Newsletter component for Mambo 4.5.1 and mixed up Newsletter and YaNC)
* Mark Lindeman <mark@pictura-dp.nl> 
    (parts of the Newsletter component by Mark Lindeman; Pictura Database Publishing bv, Heiloo the Netherland)
* Adam van Dongen <adam@tim-online.nl>
    (parts of the YaNC component by Adam van Dongen, www.tim-online.nl)

Requirementss / Rerequisites
-------------------------
Joomla 1.5.x
A mail server

The mail setup of the global configuration will be used!

Installation:
-------------------------
Just upload the zip-archive with Joomla's Component Installer.


Newsletter Management:
-------------------------
Just go to "Communicator" => "Newsletter Management" and create a new Newsletter.
Send it by clicking on "Send To" in the Newsletter List. After that you will see a confirmation page
where you can select a group of recepients to send the newsletter to.

Images in the HTML Message will be COMPLETELY embedded into the Email. 
Please be aware of the fact that images grow in size when embedded in emails because base64 encoding.


Subscriber Management:
-------------------------
Just go to "Communicator" => "Subscriber Management". There you can add, edit and delete subscribers.

You can also Import YaNC / MaMML Export Lists. Note that Letterman doesn't use the "receive_html" field.
(Users that can't read HTML, will automatically see the alternative Text body. That's not dramatic.) 
But because of that you will probably not be able to use Letterman Subscriber Export Lists with the YaNC / MaMML
Import Feature.


Facts you better know of:
-------------------------
* If you have chosen to embed images into the mail (see configuration):
	all Images in HTML will be embedded into the email, but not remote Images.
* You can use a "subscribe module" for Communicator, which shows a simple subscription form. Do
  not use YaNC, MaMML or other subscribe modules. they won't fit...
* Communicator uses the default Joomla Mail configuration - either mail(), sendmail or smtp sending.
* Communicator doesn't allow you to manage multiple Mailing Lists - most users don't need that feature
* when sending a mail with the [NAME] tag in it, all recipients will receive an email (it will be sent out again and again, using To:).
* when sending a mail without the [NAME] tag in it, one email will be sent to the site owner, all other recepients are "BCC".

* you can configure some email texts / footers in /administrator/components/com_communicator/language/english.messages.php

That's all.
thanks to Soeren Eberhardt, Mark Lindeman & Adam van Dongen for the work on their components, this component is based of.
