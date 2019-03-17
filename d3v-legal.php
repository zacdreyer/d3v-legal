<?php
/*
 Plugin Name: D3V Legal Notices ZA
 Plugin URI: http://www.d3v.software/
 Description: Output relevant legal notices as required by POPI and other laws. Usage: [d3v-legal notice='privacy' company='D3V Digital' email='optout@d3v.co.za'].
 Version: 2019.1
 Author: D3V Software
 Author URI: http://www.d3v.software/
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
        case 'privacy'          :	privacy_policy($atts['company'], $atts['address']); break;
        case 'copyright'        :	copyright($atts['company']); break;
        case 'copyrightfooter'  :	copyright_footer($atts['company']); break;
        case 'disclaimer'       :	disclaimer($atts['company']); break;
        case 'tscs'             :	tscs($atts['company'], $atts['address']); break;
        case 'comptscs'         :	comp_tscs($atts['company'], $atts['email'], $atts['address'], $atts['tel'], $atts['smp']); break;
        case 'contact'          :	contact($atts['company'], $atts['email']); break;
        case 'smr'              :	social_media_release($atts['smp'], $atts['company']); break;
        case 'smn'              :   social_media_netiquette($atts['smp'], $atts['company']); break;
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
function privacy_policy($company, $address){
    // Start HTML?>
    <div id="d3v-legal-privacy-policy" class="d3v-legal d3v-legal-privacy-policy">
        <p>This privacy policy sets out how <?php echo $company; ?> uses and protects any information that you give <?php echo $company; ?> when you use this website.</p>
        <p><?php echo $company; ?> is committed to ensuring that your privacy is protected. Should we ask you to provide certain information by which you can be identified when using this website, then you can be assured that it will only be used in accordance with this privacy statement.</p>
        <p><?php echo $company; ?> may change this policy from time to time by updating this page. You should check this page from time to time to ensure that you are happy with any changes. This policy is effective from October, 2010.</p>
        <p>&nbsp;</p>
        <p> <strong>What we collect</strong></p>
        <p>We may collect the following information:</p>
        <ul>
           <li>Name and job title;</li>
           <li>Contact information including email address;</li>
           <li>Demographic information such as postcode, preferences and interests;</li>
           <li>Other information relevant to customer surveys and/or offers.</li>
        </ul>
        <p>&nbsp;</p>
        <p><strong>What we do with the information we gather</strong></p>
        <p>We may use the information provided for the following reasons:</p>
        <ul>
           <li>Internal record keeping;</li>
           <li>We may use the information to improve our products and services;</li>
           <li>We may periodically send promotional emails about new products, special offers or other information which we think you may find interesting using the email address which you have provided;</li>
           <li>From time to time, we may also use your information to contact you for market research purposes. We may contact you by email, phone, fax or mail. We may use the information to customise the website according to your interests.</li>
        </ul>
        <p>&nbsp;</p>
        <p><strong>Security</strong></p>
        <p>We are committed to ensuring that your information is secure. In order to prevent unauthorised access or disclosure, we have put in place suitable physical, electronic and managerial procedures to safeguard and secure the information we collect online.</p>
        <p>&nbsp;</p>
        <p><strong>How we use cookies</strong></p>
        <p>A cookie is a small file which asks permission to be placed on your computer’s hard drive. Once you agree, the file is added and the cookie helps analyse web traffic or lets you know when you visit a particular site. Cookies allow web applications to respond to you as an individual. The web application can tailor its operations to your needs, likes and dislikes by gathering and remembering information about your preferences.</p>
        <p>We use traffic log cookies to identify which pages are being used. This helps us analyse data about web page traffic and improve our website in order to tailor it to customer needs. We only use this information for statistical analysis purposes and then the data is removed from the system.</p>
        <p>Overall, cookies help us provide you with a better website, by enabling us to monitor which pages you find useful and which you do not. A cookie in no way gives us access to your computer or any information about you, other than the data you choose to share with us.</p>
        <p>You can choose to accept or decline cookies. Most web browsers automatically accept cookies, but you can usually modify your browser setting to decline cookies if you prefer. This may prevent you from taking full advantage of the website.</p>
        <p>&nbsp;</p>
        <p><strong>Links To Other Websites</strong></p>
        <p>Our website may contain links to other websites of interest. However, once you have used these links to leave our site, you should note that we do not have any control over that other website. Therefore, we cannot be responsible for the protection and privacy of any information which you provide whilst visiting such sites and such sites are not governed by this privacy statement. You should exercise caution and look at the privacy statement applicable to the website in question.</p>
        <p>&nbsp;</p>
        <p><strong>Controlling Your Personal Information</strong></p>
        <p>You may choose to restrict the collection or use of your personal information in the following ways:</p>
        <ul>
           <li>Whenever you are asked to fill in a form on the website, look for the box that you can click to indicate that you do not want the information to be used by anybody for direct marketing purposes;</li>
           <li>If you have previously agreed to us using your personal information for direct marketing purposes, you may change your mind at any time by writing to or emailing us through our contact form.</li>
        </ul>
        <p>We will not sell, distribute or lease your personal information to third parties unless we have your permission or are required by law to do so. We may use your personal information to send you promotional information about third parties which we think you may find interesting if you tell us that you wish this to happen.</p>
        <p>You may request details of personal information which we hold about you under the Data Protection Act 1998. A small fee will be payable. If you would like a copy of the information held on you please write <?php echo $address; ?>.</p>
        <p>If you believe that any information we are holding on you is incorrect or incomplete, please write to or email us as soon as possible, at the above address. We will promptly correct any information found to be incorrect.</p>
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
