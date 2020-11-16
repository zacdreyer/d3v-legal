<?php
/*
 Plugin Name: D3V Legal Notices ZA
 Plugin URI: http://www.d3v.software/
 Description: Output relevant legal notices as required by POPI and other laws. Usage: [d3v-legal notice='privacy' company='D3V Digital' email='optout@d3v.co.za'].
 Version: 2019.1
 Author: D3V Software
 Author URI: https://github.com/D3V-Software/
 Text Domain: legalnotices

 This program is free software; you can redistribute it and/or
 modify it under the terms of the GNU General Public License
 as published by the Free Software Foundation; either version 2
 of the License, or (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program; if not, write to the Free Software
 Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */


// Add Shortcode
function d3v_legal_notices( $atts ) {

    // Attributes
    $atts = shortcode_atts(
        array(
            'notice'    => '',
            'company'   => '',
            'email'     => '',
            'address'   => '',
            'tel'       => '',
            'smp'		=> '',
        ),
        $atts
    );

    switch($atts['notice']){
        case 'cookies'          :	cookies(); break;
        case 'privacy'          :	privacy_policy($atts['company'], $atts['address'], $atts['email'], $atts['tel']); break;
        case 'copyright'        :	copyright($atts['company']); break;
        case 'copyrightfooter'  :	copyright_footer($atts['company']); break;
        case 'disclaimer'       :	disclaimer($atts['company']); break;
        case 'tscs'             :	tscs($atts['company'], $atts['address']); break;
        case 'comptscs'         :	comp_tscs($atts['company'], $atts['email'], $atts['address'], $atts['tel'], $atts['smp']); break;
        case 'contact'          :	contact($atts['company'], $atts['email']); break;
        case 'smr'              :	social_media_release($atts['smp'], $atts['company']); break;
        case 'smn'              : social_media_netiquette($atts['smp'], $atts['company']); break;
        default                 :	echo ''; break;
    }

}
add_shortcode( 'd3v-legal', 'd3v_legal_notices' );


// Cookie Notice
function cookies(){
    // Start HTML?>
    <div id="d3v-legal-cookies" class="d3v-legal d3v-legal-cookies">
        <p>This website makes use of cookies to help us provide you with a better website experience, by enabling us to monitor which pages you find useful and which you do not. A cookie in no way gives us access to your computer or any information about you, other than the data you choose to share with us.</p>
        <p>You can choose to accept or decline cookies. Most web browsers automatically accept cookies, but you can usually modify your browser setting to decline cookies if you prefer. This may prevent you from taking full advantage of the website.</p>
    </div>
    <?php // End HTML
}

// Privacy Policy Statement
function privacy_policy($company, $address, $email, $tel){
    // Start HTML?>
    <div id="d3v-legal-privacy-policy" class="d3v-legal d3v-legal-privacy-policy">
        <p><?php echo $company; ?> services, including (without limitation) our website and other interactive properties through which the services are delivered (collectively, the “Service”) are owned, operated and distributed by <?php echo $company; ?> (referred to in this Privacy Notice as “<?php echo $company; ?>” or “we” and through similar words such as “us,” “our,” etc.). This Privacy Notice outlines the personal information that <?php echo $company; ?> may collect, how <?php echo $company; ?> uses and safeguards that information, and with whom we may share it.</p>

	<p>&nbsp;</p>
	<p><?php echo $company; ?> encourages our customers, visitors, business associates, and other interested parties to read this Privacy Notice, which applies to all users. By using our Service or submitting personal information to <?php echo $company; ?> by any other means, you acknowledge that you understand and agree to be bound by this Privacy Notice, and agree that <?php echo $company; ?> may collect, process, transfer, use, and disclose your personal information as described in this Notice. Further, by accessing any part of the Service, you are agreeing to THE TERMS AND CONDITIONS OF OUR TERMS OF SERVICE (the “Terms of Service”). IF YOU DO NOT AGREE WITH ANY PART OF THIS PRIVACY NOTICE OR OUR TERMS OF SERVICE, PLEASE DO NOT USE ANY OF THE SERVICES.</p>

	<p>&nbsp;</p>
	<p><strong>What personal information do we collect about you?</strong></p>
	<p>Personal information (also commonly known as personally identifiable information (PII) or personal data) is information that can be used to identify you, or any other individual to whom the information may relate.</p>

	<p>&nbsp;</p>
	<p>The personal information that we collect directly from those registering for the Service, includes the following categories:</p>
	<ul>
		<li>Name and contact information (e.g. address; phone number; email, fax);</li>
		<li>Billing Information (e.g. credit card, bank account, billing contact information);</li>
		<li>Order Information (e.g. current order/purchase information, purchase history, shipping details);</li>
		<li>Travel Information (e.g. booking numbers, passport information, flight numbers, travel details);</li>
		<li>Company/employer information;</li>
		<li>Geographic or location information;</li>
		<li>Information contained in posts you may on the public forums and interactive features of the Service.;</li>
		<li>Other information that may be exchanged in the course of engaging with the Service.  You will be aware of any subsequently collected information because it will come directly from you.;</li>
	</ul>

	<p>&nbsp;</p>
	<p><strong>Collection of User Generated Content</strong></p>
	<p>We may invite you to post content on the Service, including your comments and any other information that you would like to be available on the Service, which may become public (“User Generated Content”). If you post User Generated Content, all of the information that you post will be available to authorized personnel of <?php echo $company; ?>. You expressly acknowledge and agree that we may access in real-time, record and store archives of any User Generated Content on our servers to make use of them in connection with the Service. If you submit a review, recommendation, endorsement, or other User Generated Content through the Service, or through other websites including Facebook, Instagram, Google, Yelp, and other similar channels, we may share that review, recommendation, endorsement or content publicly on the Service.</p>

	<p>&nbsp;</p>
	<p><strong>What are the sources of personal information collected by <?php echo $company; ?>?</strong></p>
	<p>When providing personal information to <?php echo $company; ?> as described in this Notice, that personal information is collected directly from you, and you will know the precise personal information being collected by us. <?php echo $company; ?> does not collect personal information from any other sources, except where it may automatically be collected as described in the section titled “Cookies, Device Data, and How it is Used, if the information in that section is considered personal information.</p>

	<p>&nbsp;</p>
	<p><strong>Why does <?php echo $company; ?> collect your personal information?</strong></p>
	<p>Subject to the terms of this Privacy Notice, <?php echo $company; ?> uses the above described categories of personal information in several ways. Unless otherwise stated specifically, the above information may be used for any of the following purposes:</p>
	<ul>
		<li>to administer the Service to you;</li>
		<li>to respond to your requests;</li>
		<li>to distribute communications relevant to your use of the Service, such as system updates or information about your use of the Service;</li>
		<li>as may be necessary to support the operation of the Service, such as for billing, account maintenance, and record-keeping purposes;</li>
		<li>to send to you <?php echo $company; ?> solicitations, product announcements, and the like that we feel may be of interest to  you. Please note that you may “opt out” of receiving these marketing materials;</li>
		<li>in other manners after subsequent notice is provided to you and/or your consent is obtained, if necessary.</li>
	</ul>
	<p><?php echo $company; ?> does not sell, re-sell, or distribute for re-sale your personal information.</p>

	<p>&nbsp;</p>
	<p><strong>Direct Marketing Communications</strong></p>
	<p>We may communicate with you using email, SMS, Social Media, and other channels (sometimes through automated means) as part of our effort to market our products or services, administer or improve our products or services, or for other reasons stated in this Privacy Notice. You have an opportunity to withdraw consent to receive such direct marketing communications, as permitted by law.</p>

	<p>&nbsp;</p>
	<p>If you no longer wish to receive correspondence, emails, or other communications from us, you may opt-out by submitting a request through via email to <?php echo $email; ?>, or by using the UNSUBSCRIBE link in any email communication you may have received.</p>

	<p>&nbsp;</p>
	<p>Please note that you may continue to receive non-marketing communications as may be required to maintain your relationship with <?php echo $company; ?>.</p>

	<p>&nbsp;</p>
	<p>In addition to the communication described here, you may receive third-party marketing communications from providers we have engaged to market or promote our products and services. These third-party providers may be using communications lists they have acquired on their own, and you may have opted-in to those lists through other channels.  If you no longer wish to receive emails, SMSs, or other communications from such third parties, you may need to contact that third party directly.</p>

	<p>&nbsp;</p>
	<p><strong>Retention of Data</strong></p>
	<p><?php echo $company; ?> will retain your personal information only for as long as is necessary for the purposes set out in this Notice. We will retain and use personal information to the extent necessary to comply with our legal obligations (for example, if we are required to retain your data to comply with applicable laws), resolve disputes and enforce our legal agreements and policies.</p>

	<p>&nbsp;</p>
	<p><?php echo $company; ?> will also retain usage data for internal analysis purposes. Usage data is generally retained for a shorter period of time, except when this data is used to strengthen the security or to improve the functionality of our Sites and/or Portals, or we are legally obligated to retain this data for longer periods.</p>

	<p>&nbsp;</p>
	<p><strong>Cookies, Device Data, and How it is Used</strong></p>
	<p>When you use our Service, we may record unique identifiers associated with your device (such as the device ID and IP address), your activity within the Service, and your network location. <?php echo $company; ?> uses aggregated information (such as anonymous user usage information, cookies, IP addresses, browser type, clickstream information, etc.) to improve the quality and design of the Service and to create new features, promotions, functionality, and services by storing, tracking, and analyzing user preferences and trends. Specifically, we may automatically collect the following information about your use of Service through cookies, web beacons, and other technologies:</p>
	<ul>
		<li>domain name;
		<li>browser type and operating system;
		<li>web pages you view;
		<li>links you click;
		<li>IP address;
		<li>the length of time you visit the Sites, Portals, and/or Services;
		<li>the referring URL or the webpage that led you to the Sites;
	</ul>

	<p>&nbsp;</p>
	<p>We may also collect information regarding application-level events, such as crashes, and associate that temporarily with your account to provide customer service. In some circumstances, we may combine this information with personal information collected from you (and third-party service providers may do so on behalf of us).</p>

	<p>&nbsp;</p>
	<p>In addition, we may use "cookies," clear gifs, and log file information that help us determine the type of content and pages to which you link, the length of time you spend at any particular area of the Service, and the portion of the Service you choose to use. A cookie is a small text file that is sent by a website to your computer or mobile device where it is stored by your web browser. A cookie contains limited information, usually a unique identifier and the name of the site. Your browser has options to accept, reject or provide you with notice when a cookie is sent. Our cookies can only be read by <?php echo $company; ?>; they do not execute any code or virus; <strong>and they do not contain any personal information</strong>. Cookies allow <?php echo $company; ?> to serve you better and more efficiently, and to personalize your experience with the Service. We may use cookies for many purposes, including (without limitation) to save your password so you don’t have to re-enter it each time you visit the Service, and to deliver content (which may include third party advertisements) specific to your interests.</p>

	<p>&nbsp;</p>
	<p>We may use third party service providers to help us analyze certain online activities. For example, these service providers may help us measure the performance of our online campaigns or analyze visitor activity on the Service. We may permit these service providers to use cookies and other technologies to perform these services for <?php echo $company; ?>. We do not share any personal information about our customers with these third-party service providers, and these service providers do not collect such personal information on our behalf. Our third-party service providers are required to comply fully with this Privacy Notice.</p>

	<p>&nbsp;</p>
	<p>For individuals located outside the South Africa, in particular in Switzerland, the United Kingdom and the European Economic Area (EEA), please note that <?php echo $company; ?> is a South African based company. <?php echo $company; ?> does not market to or solicit customers from outside the South Africa, therefore, users of the Service should not expect to avail themselves of the rights provided under the EU’s General Data Protection Regulation (“GDPR”). If you use the Service, all information, including personal information, will be transferred to <?php echo $company; ?> in South Africa. By using the Service, you unambiguously consent to the transfer of your personal information and other information to the South Africa and elsewhere for the purposes and uses described in this Notice. Further, you acknowledge that <?php echo $company; ?> is not subject to the GDPR or similar international privacy laws, and, therefore, you will be unable to claim the privacy rights provided in those laws.</p>

	<p>&nbsp;</p>
	<p>We may use third party service providers to help us deliver certain services, and it may result in the processing of personal information in data centers and locations outside of the South Africa. For example, these service providers may provide us with essential information technology or tools we use to run our business. We may permit these service providers to process our business information and/or your personal information. We do not permit these service providers to process any personal information outside of a contract, and these service providers may collect personal information on our behalf. Our third-party service providers are required to comply fully with this Privacy Notice.</p>

	<p>&nbsp;</p>
	<p><strong>European Union Data Privacy Rights</strong></p>
	<p>If you are located in the EEA, the General Data Protection Regulation (“GDPR”) grants you certain rights under the law. In particular, the right to access, correct, and delete the personal information we hold about you. <?php echo $company; ?> will retain your personal information for the length of time you engage with our services as described in the retention section of this Notice, until you request deletion of such personal information.</p>

	<p>&nbsp;</p>
	<p>In certain circumstances, you have the following data protection rights:</p>
	<ul>
		<li>The right to access, update, or delete the personal information we have on you;</li>
		<li>The right of rectification. You have the right to have your personal information rectified if that information is inaccurate or incomplete;</li>
		<li>The right to object. You have the right to object to our processing of your personal informationl;</li>
		<li>The right of restriction. You have the right to request that we restrict the processing of your personal information;</li>
		<li>The right to data portability. You have the right to be provided with a copy of the personal information we have on you in a structured, machine-readable and commonly used format;</li>
		<li>The right to withdraw consent. You also have the right to withdraw your consent at any time where we relied on your consent to process your personal information.</li>
	</ul>

	<p>&nbsp;</p>
	<p>In order make a request regarding your personal information, please contact us via e-mail on <?php echo $email; ?>.</p>

	<p>&nbsp;</p>
	<p>If you have a comment, question, or complaint about how we are handling your personal information, we hope that you contact us at <?php echo $email; ?> in order to allow us to resolve the matter. In addition, if you are located in the EEA, you may submit a complaint regarding the processing of your personal information to the EU data protection authorities (each a “DPA”). The following link may assist you in finding the appropriate DPA: <a href="https://ec.europa.eu/justice/data-protection/bodies/authorities/index_en.htm" target="_blank">https://ec.europa.eu/justice/data-protection/bodies/authorities/index_en.htm</a></p>

	<p>&nbsp;</p>
	<p><strong>Legal Basis for Processing under GDPR</strong></p>
	<p>If you are located in the EEA, our legal basis for collecting and using the personal information described in this Notice depends on the personal information we collect and the specific context in which we collect it.</p>

	<p>&nbsp;</p>
	<p>We may process personal information because:</p>
	<ul>
		<li>We need to perform a contract with you;</li>
		<li>You have given us consent to do so;</li>
		<li>The processing is in our legitimate interest to offer the Service, when that legitimate interest is not overridden by your rights;</li>
		<li>To comply with the law.</li>
	</ul>

	<p>&nbsp;</p>
	<p>Where personal information is processed based on consent, EU residents have the right to withdraw such consent at any time. To do so, please contact us as described in this Notice. If there is a different legal basis that would permit us to continue processing your personal information after withdrawing consent, we will notify you of that legal basis at the time of your request.</p>

	<p>&nbsp;</p>
	<p><strong>South African Privacy Rights</strong></p>
	<p>If you are a South African resident, South Africa law may provide you with certain rights with regard to your personal information under the Protection of Personal Information Act (“POPIA”) and Promotion of Access to Information Act (“PAIA”).as well the Consumer Protection Act Throughout this Privacy Notice you will find information required by POPIA regarding the categories of personal information collected from you; the purposes for which we use personal information, and the categories of third parties your data may be shared with.  This information is current as of the date of the Notice and is applicable in the 12 months preceding the effective date of the Notice.</p>

	<p>&nbsp;</p>
	<p>As a South African resident, the POPIA and PAIA provide you the ability to make inquiries regarding to your personal information. Specifically, the degree to which the information is not already provided in this Privacy Notice, you have the right to request disclosure or action your personal information, including:</p>
	<ul>
		<li>If your personal information is collected by us.</li>
		<li>The specific pieces of personal information collected about you.</li>
		<li>The ability to correct or delete certain personal information collected about you.</li>
		<li>The ability to delete all the personal information collected about you, subject to certain exceptions.</li>
		<li>To opt-in or opt-out of direct marketing to you.</li>
		<li>To object to processing of your personal information, or </li>
		<li>Appeal any rejection of access to your personal information</li>
	</ul>

	<p>&nbsp;</p>
	<p>You may submit a request regarding your rights under POPIA or PAIA by submitting a request through e-mailing <?php echo $email; ?> or by contacting us in writing at one of the following address: <?php echo $address; ?>.</p>

	<p>&nbsp;</p>
	<p>If we receive a POPIA request from you, we will first make a determination regarding the applicability of the law, and we will then take steps to verify your identity prior to responding. The steps to verify your identity may vary based on our relationship with you, but, at a minimum, it will take the form of confirming and matching the information submitted in the request with information already held by <?php echo $company; ?> and/or contacting you through previously used channels to confirm that you submitted the request (i.e. confirming identity through contact information that we have on file, and/or the contact information submitted to make the request).</p>

	<p>&nbsp;</p>
	<p>The <?php echo $company; ?> does not knowingly collect or process the special personal information such as your religious or philosophical beliefs, race or ethnic origins, trade union memberships, political persuasion, health or sex life, or your criminal behavior or biometric information.</p>

	<p>&nbsp;</p>
	<p>If you have a comment, question, or complaint about how we are processing your personal information, we hope that you contact us at <?php echo $email; ?> in order to allow us to resolve the matter. In addition, if you are located in the Republic of South Africa, you may submit a complaint regarding the processing of your personal information to the Information Regulator at the following link: <a href="https://www.justice.gov.za/inforeg/contact.html" target="_blank">https://www.justice.gov.za/inforeg/contact.html</a>.</p>

	<p>&nbsp;</p>
	<p><strong>Third Party Advertisers</strong></p>
	<p>We may allow other companies, called third-party ad servers or ad networks, to serve advertisements within the Service. These third-party ad servers or ad networks use technology to send, directly to your device, the advertisements and links that appear on the Service. They automatically receive your device ID and IP address when this happens. They may also use other technologies (such as cookies, JavaScript, or Web Beacons) to measure the effectiveness of their advertisements and to personalize the advertising content you see. You should consult the respective privacy policies of these third-party ad servers or ad networks for more information on their practices and for instructions on how to opt-out of certain practices. This Privacy Notice does not apply to them, and we cannot control their activities.</p>

	<p>&nbsp;</p>
	<p><strong>Information Storage and Security</strong></p>
	<p>We employ industry-standard and/or generally accepted security measures designed to secure the integrity and confidentiality of all information submitted through the Service. However, the security of information transmitted through the internet or via a mobile device can never be guaranteed. We are not responsible for any interception or interruption of any communications through the internet or for changes to or losses of data.</p>

	<p>&nbsp;</p>
	<p>Users of the Service are responsible for maintaining the security of any password, user ID or other form of authentication involved in obtaining access to password protected or secure areas of the Service. In order to protect you and your information, we may suspend your use of any of the Service, without notice, pending an investigation, if any breach of security is suspected.</p>

	<p>&nbsp;</p>
	<p><strong>External Links</strong></p>
	<p>The Service may contain links to other websites maintained by third parties. Please be aware that we exercise no control over linked sites and <?php echo $company; ?> is not responsible for the privacy practices or the content of such sites. Each linked site maintains its own independent privacy and data collection policies and procedures, and you are encouraged to view the privacy policies of these other sites before providing any personal information.</p>

	<p>&nbsp;</p>
	<p>You hereby acknowledge and agree that <?php echo $company; ?> is not responsible for the privacy practices, data collection policies and procedures, or the content of such third-party sites, and you hereby release <?php echo $company; ?> from any and all claims arising out of or related to the privacy practices, data collection policies and procedures, and/or the content of such third-party sites.</p>

	<p>&nbsp;</p>
	<p><strong>Children’s Privacy</strong></p>
	<p>The Service is not intended for children under the age of 18, and <?php echo $company; ?> does not knowingly collect the personal information of children under the age of 18.</p>

	<p>&nbsp;</p>
	<p><strong>Changes to this Privacy Notice</strong></p>
	<p><?php echo $company; ?> reserves the right to modify this Privacy Notice from time to time in order that it accurately reflects the regulatory environment and our data collection principles. When material changes are made to this Privacy Notice, <?php echo $company; ?> will post the revised Notice on our website. This Privacy Notice was last modified as of 13 November 2020.</p>

	<p>&nbsp;</p>
	<p><strong>Contact Us</strong></p>
	<p>If you have any questions or comments about this Privacy Notice or the Service provided by <?php echo $company; ?>, please contact us, by writing <?php echo $address; ?> or via e-mail at <?php echo $email; ?> or telephonically via <?php echo $tel; ?>.</p>
    </div>
    <?php // End HTML
}

// Copyright Notice
function copyright($company){
    // Start HTML?>
    <div id="d3v-legal-copyright" class="d3v-legal d3v-legal-copyright">
        <p>This website and its content is copyright of <?php echo $company; ?> &copy; <?php echo date('Y'); ?>. All rights reserved.</p>
        <p>Any redistribution or reproduction of part or all of the contents in any form is prohibited other than the following:</p>
        <ul>
           <li>You may print or download to a local hard disk extracts for your personal and non-commercial use only;</li>
           <li>You may copy the content to individual third parties for their personal use, but only if you acknowledge the website as the source of the material.</li>
        </ul>
        <p>All logos and brands are copyright to their respective owners and are protected under copyright laws.</p>
        <p>You may not, except with our express written permission, distribute or commercially exploit the content. Nor may you transmit it or store it in any other website or other form of electronic retrieval system.</p>
    </div>
    <?php // End HTML
}

// Copyright Footer
function copyright_footer($company){
    // Start HTML?>
    <div id="d3v-legal-copyright-footer" class="d3v-legal d3v-legal-copyright-footer">
        <p>Copyright <?php echo $company; ?> &copy; <?php echo date('Y'); ?>. All rights reserved.</p>
    </div>
    <?php // End HTML
}

// Disclaimer
function disclaimer($company){
    // Start HTML?>
    <div id="d3v-legal-disclaimer" class="d3v-legal d3v-legal-disclaimer">
        <p>The information contained in this website is for general information purposes only. The information is provided by <?php echo $company; ?> and while we endeavour to keep the information up to date and correct, we make no representations or warranties of any kind, express or implied, about the completeness, accuracy, reliability, suitability or availability with respect to the website or the information, products, services, or related graphics contained on the website for any purpose. Any reliance you place on such information is therefore strictly at your own risk.</p>
        <p>In no event will we be liable for any loss or damage including without limitation, indirect or consequential loss or damage, or any loss or damage whatsoever arising from loss of data or profits arising out of, or in connection with, the use of this website.</p>
        <p>Through this website you are able to link to other websites which are not under the control of <?php echo $company; ?>. We have no control over the nature, content and availability of those sites. The inclusion of any links does not necessarily imply a recommendation or endorse the views expressed within them.</p>
        <p>Every effort is made to keep the website up and running smoothly. However, <?php echo $company; ?> takes no responsibility for, and will not be liable for, the website being temporarily unavailable due to technical issues beyond our control.</p>
    </div>
    <?php //End HTML
}

// Terms and Conditions
function tscs($company, $address){
    // Start HTML?>
    <div id="d3v-legal-tscs" class="d3v-legal d3v-legal-tscs">
        <p>Welcome to our website. If you continue to browse and use this website, you are agreeing to comply with and be bound by the following terms and conditions of use, which together with our privacy policy govern <?php echo $company; ?> relationship with you in relation to this website. If you disagree with any part of these terms and conditions, please do not use our website.</p>
        <p>The term '<?php echo $company; ?>' or 'us' or 'we' refers to the owner of the website whose registered office is <?php echo $address; ?>. The term 'you' refers to the user or viewer of our website.</p>
        <p>The use of this website is subject to the following terms of use:</p>
        <ul>
           <li>The content of the pages of this website is for your general information and use only. It is subject to change without notice.</li>
           <li>Neither we nor any third parties provide any warranty or guarantee as to the accuracy, timeliness, performance, completeness or suitability of the information and materials found or offered on this website for any particular purpose. You acknowledge that such information and materials may contain inaccuracies or errors and we expressly exclude liability for any such inaccuracies or errors to the fullest extent permitted by law.</li>
           <li>Your use of any information or materials on this website is entirely at your own risk, for which we shall not be liable. It shall be your own responsibility to ensure that any products, services or information available through this website meet your specific requirements.</li>
           <li>This website contains material which is owned by or licensed to us. This material includes, but is not limited to, the design, layout, look, appearance and graphics. Reproduction is prohibited other than in accordance with the copyright notice, which forms part of these terms and conditions.</li>
           <li>All trademarks reproduced in this website, which are not the property of, or licensed to the operator, are acknowledged on the website.</li>
           <li>Unauthorised use of this website may give rise to a claim for damages and/or be a criminal offence.</li>
           <li>From time to time, this website may also include links to other websites.</li>
           <li>These links are provided for your convenience to provide further information. They do not signify that we endorse the website(s). We have no responsibility for the content of the linked website(s).</li>
           <li>Your use of this website and any dispute arising out of such use of the website is subject to the laws of South Africa.</li>
        </ul>
    </div>
    <?php // End HTML
}

// Competition Terms and Conditions
function comp_tscs($company, $email, $address, $tel, $social_media_platform){
    // Start HTML?>
    <div id="d3v-legal-comp-tscs" class="d3v-legal d3v-legal-comp-tscs">
        <p><strong>Please read these terms &amp; conditions carefully. By entering </strong><strong><?php echo $company; ?> competition all participants will be deemed to have accepted and be bound by these terms and conditions. &nbsp;If you do not agree to these terms and conditions, you must not enter the competition.</strong>&nbsp; <strong>Please retain a copy of these terms and conditions for your information.</strong></p>
        <p>&nbsp;</p>
        <p><strong>Promoter</strong>></p>
        <p>The promoter is <?php echo $company; ?>, <?php echo $address; ?>, <?php echo $tel; ?>.</p>
        <p>&nbsp;</p>
        <p><strong>Participants</strong></p>
        <ol start="1" type="1">
            <ol start="1" type="1">
                <li>The competition is open to South African  residents only. Participants must be 18 years or older.</li>
                <li>The competition is not open to directors,  members, partners, employees, agents or consultants of the promoter, any  person who controls or is controlled by the promoter, or any supplier of  goods or services in connection with the competition, or their respective  spouses, life partners, business partners or immediate family members.</li>
            </ol>
        </ol>
        <p>&nbsp;</p>
        <p><strong>Entry</strong></p>
        <ol start="2" type="1">
            <ol start="1" type="1">
                <li>Enter the competition via the <?php echo $company; ?> website or other mechanisms specified in the competition.</li>
                <li>Each winner will be the first entry selected  at random from all qualifying entries.</li>
                <li>The prize winner will be drawn on the day  after entries close, and announced on the following day on the <?php echo $social_media_platform; ?>  page.</li>
                <li>The promoter will attempt to make contact  with each winner at least 3 times, but if it is unable to contact any  winner within one week of the relevant draw, the winner will forfeit  his/her prize and the promoter reserves the right to select a new winner.</li>
                <li>Winners must provide their ID numbers for  regulatory purposes.</li>
                <li>Entries submitted after the closing date will  be disqualified.</li>
            </ol>
        </ol>
        <p>&nbsp;</p>
        <p><strong>Prizes</strong></p>
        <ol start="3" type="1">
            <ol start="1" type="1">
                <li>All participants will have an equal chance of  winning prizes.</li>
                <li>The prizes will be sent by courier to the  address the winners provide to the promoter, by no later than four weeks  after each winner announcement. The promoter will not be held responsible  for any failure by the courier to deliver a prize, or any winner&rsquo;s failure to accept delivery of his/her prize.</li>
                <li>The prizes cannot be exchanged or refunded  and there is no cash alternative.</li>
                <li>The prizes are non-transferable.</li>
                <li>The prize does not include any other costs or  expenses relating to the prize or the enjoyment of the prize not  expressly specified in these terms and conditions.</li>
            </ol>
        </ol>
        <p>&nbsp;</p>
        <p><strong>Publicity and Personal Information</strong></p>
        <ol start="4" type="1">
            <ol start="1" type="1">
                <li>The winners agree to the use of their names  and images in any publicity material. The winners have the right to  decline this by notifying the promoter at <?php echo $email; ?>.</li>
                <li>By entering the competition, participants  consent to the collection, use, storage, disclosure and processing of  their personal information by the promoter for a reasonable period for  the purposes of administering the competition, providing the prize and  other activities as contemplated in these terms and conditions. The types  of personal information that the promoter may collect includes  information necessary for its legitimate business interests and the  categories of personal information identified in relevant data protection  laws in South Africa.&nbsp; This may include participants&rsquo; names, identity  numbers, e-mail, physical and postal addresses, contact information, and  other information provided when entering and participating in the  competition.</li>
                <li>The promoter may use participants&rsquo; personal  information: </li>
                <ol start="1" type="1">
                    <li>to update the promoter&rsquo;s existing records;</li>
                    <li>for the purpose of administering customer   relations; and</li>
                    <li>to make information available on future competitions or promotions which the promoter may conduct.</li>
                </ol>
            </ol>
        </ol>
        <p>&nbsp;</p>
        <p><strong>General Provisions</strong></p>
        <ol start="5" type="1">
            <ol start="1" type="1">
                <li>In the event of a <?php echo $social_media_platform; ?> based promotion,  the promotion is in no way sponsored, endorsed or administered by, or  associated with <?php echo $social_media_platform; ?>.</li>
            </ol>
        </ol>
        <p>The promoter reserves the right, acting reasonably, to cancel or amend the competition due to events or circumstances arising beyond its control which prevent the promoter from conducting the competition as intended.&nbsp; Participants are entitled to withdraw from the competition if they do not agree with any amendments made by the promoter. As far as the law allows, if the promoter cancels the competition or amends these terms and conditions, participants will have no claim against the promoter.</p>
        <ol start="1" type="1">
            <li>The promoter reserves the right, acting reasonably, to disqualify any participant who does not comply with these terms and conditions.</li>
            <li>Participants agree to keep confidential any information of whatsoever nature regarding the promoter, its associated companies, and their respective directors, officers, employees and agents received as a result of winning or participating in the competition.</li>
            <li>These terms and conditions shall be governed by the laws of South Africa. Participants consents to the non-exclusive jurisdiction of the High Court (Gauteng Local Division, Johannesburg) in respect of all matters arising out of or in connection with the competition or these terms and conditions.</li>
            <li>If any provision of these terms and conditions is found to be invalid or unenforceable by any court of competent jurisdiction, then that provision shall be severed from these terms and conditions and shall not affect the validity or enforceability of any remaining provisions.</li>
            <li>It is not intended that any provision of these terms and conditions contravenes any provision of the Consumer Protection Act, 68 of 2008, and therefore all provisions of these terms and conditions must be treated as being qualified, if necessary, to ensure that the provisions of the Consumer Protection Act are complied with.</li>
            <li>A copy of these terms and conditions may be obtained by contacting the promoter at <?php echo $email; ?>.</li>
        </ol>
        <p>&nbsp;</p>
        <p><strong>Errors and Omissions</strong></p>
        <p>While every care has been exercised in presenting this competition, <?php echo $company; ?> accepts no responsibility for errors or omissions.</p>
    </div>
    <?php //End HTML
}

// Data Administration Contact
function contact($company, $email){
    // Start HTML ?>
    <div id="d3v-legal-contact" class="d3v-legal d3v-legal-contact">
        <p>If you have any queries about the manner in which this form is administered and/or your data is used by <?php echo $company; ?> and/or should you require us to remove your email address from our systems, please contact: <?php echo $email; ?></p>
    </div>
    <?php // End HTML
}

// Social Media Release Statement
function social_media_release($social_media_platform, $company){
    // Start HTML?>
    <div id="d3v-legal-smr" class="d3v-legal d3v-legal-smr">
        <p>This activity is in no way sponsored, endorsed or administered by, or associated with <?php echo $social_media_platform; ?>. By submitting this form you agree to a complete release of <?php echo $social_media_platform; ?>. You understand that you are providing your information to <?php echo $company; ?> and not to <?php echo $social_media_platform; ?>.</p>
    </div>
    <?php // End HTML
}

// Social Media Netiquette
function social_media_netiquette($social_media_platform, $company){
    // Start HTML?>
    <div id="d3v-legal-smn" class="d3v-legal d3v-legal-smn">
        <h2>Welcome to the <?php echo $company; ?> <?php echo $social_media_platform; ?> community!</h2>
        <p><?php echo $company; ?> encourages all fans to contribute to this community to create a fun, lively and secure environment. For this to happen in a relaxed and friendly atmosphere, we ask you to follow a few rules.</p>
        <p>Although we hope that this will not be necessary, we reserve the right, in extreme cases, to intervene in the discussion and to remove comments or posts that contravene these rules.</p>
        <p><strong>Relevance</strong></p>
        <p>Our social media communities are spaces for you to discuss topics relevant to our business, for us to communicate with you about our goods and services, and for us to connect you to our official website. You should only publish content that is relevant. Anything contrary to our community purpose will be irrelevant and removed, including:</p>
        <ul>
            <li>Links to external websites not related to us or our goods and services;</li>
            <li>Adverts for goods or services other than ours - like your goods or services or those of a competitor;</li>
            <li>Promotional competitions other than ours - like your promotional competitions or those of a competitor;</li>
            <li>Spam or anything else published more than once or in more than one place;</li>
            <li>Donation requests unless we have authorised you to publish them in writing;</li>
            <li>Acknowledgement requests - like asking for 'votes', 'likes', or 'retweets';</li>
            <li>Your personal contact information because it puts your privacy at risk - like your email address or phone number; or</li>
            <li>Anything else that isn't relevant to the purpose or conversations on the social media community in question. </li>
        </ul>
        <p>&nbsp;</p>
        <p><strong>Moderation</strong></p>
        <p>We may edit or remove anything that you publish to our social media communities that are inconsistent with these rules, including anything irrelevant and any conduct, content, or promotional competition related content that is prohibited.</p>
        <p>We may moderate any of the following things as described above among others:</p>
        <ul>
            <li>Anything that is not relevant to a social media community or the conversation in question;</li>
            <li>Anything that results from prohibited conduct, including discrimination, hate speech, harassment, or trolling;</li>
            <li>Prohibited content, including content that is illegal, harmful, offensive, abusive, racist, sexist, homophobic, vulgar, obscene, pornographic, offensive, harassing, hateful, threatening, defamatory, indecent or impermissible;</li>
            <li>Fraudulent, deceptive, or misleading, in violation of a copyright, trademark or other intellectual property right of another;</li>
            <li>Prohibited promotional content, including unjustified outcries, discriminatory objections, or otherwise objectionable to us.</li>
        </ul>
        <p>&nbsp;</p>
        <p><strong>Not our views</strong></p>
        <p>What you and other members publish on our social media communities are not our views. We do not endorse anything you publish to our social media communities merely by acknowledging it in any way, such as a 'like', 'retweet', 'favourite'.</p>
        <p>&nbsp;</p>
        <p><strong>Report</strong></p>
        <p>If you see any behavior from other community members who are in breach of these rules, we encourage you to report them to us through an inbox message to the community.</p>
        <p>&nbsp;</p>
        <p><strong>Recourse</strong></p>
        <p>We may take recourse against you for breaching these rules by suspending or banning you from any of our social media communities.</p>
        <p>&nbsp;</p>
        <p>Should you have any further enquiries, please contact us via <?php echo $social_media_platform; ?>'s direct messaging platform.</p>
        <p><?php echo $company; ?> would like to thank you for taking the time to read an understand the above.</p>
    </div>
    <?php // End HTML
}
?>
