<?php

namespace App\Enums;

enum EntryTypeIncident:string {

    //A. Offenses Againsts Confidentiality, Integrity and Availability
    case ACCESS = 'Sec. 4(a)(1) Illegal Access';
    case INTERCEPTION = 'Sec. 4(a)(2) Illegal Interception';
    case DATA = 'Sec. 4(a)(3) Data Interference';
    case SYSTEM = 'Sec. 4(a)(4) System Interference';
    case MISUSE = 'Sec. 4(a)(5) Misuse of the Devices';
    case CYBER = 'Sec. 4(a)(6) Cyber-Squatting';

    //B. Computer-Related Offenses
    case FORGERY = 'Sec. 4(b)(1) Computer-Related Forgery';
    case FRAUD = 'Sec. 4(b)(2) Computer-Related Fraud';
    case IDENTITY = 'Sec. 4(b)(3) Computer-Related Identity Theft';

    //C. Content-Related Offenses
    case CYBERSEX = 'Sec. 4(c)(1) Cybersex';
    case CHILD = 'Sec. 4(c)(2) Child Pornography/RA 9775';
    case UNSOLICITED = 'Sec. 4(c)(3) Unsolicited Commercial Communication';
    case LIBEL = 'Sec. 4(c)(4) Libel/Act. 355';

    //D. Other Offenses
    case AIDING = 'Sec. 5(a)(1) Aiding/Abetting in Commission of Cybercrime';
    case ATTEMPT = 'Sec. 5(b)(2) Attempt in the Commission of Crime';

    //E. Sec. 6/All RPC/Special Laws Committed thru the use of ICT
    case ANTI_PHOTO = 'RA 9995 (Anti Photo and Video Voyeurism Act)';
    case EXPANDED = 'RA 10364 / RA 9208 (Expanded Trafficking in Person Act)';
    case VIOLENCE = 'RA 9262 (Violence Against Woman and their Children)';
    case SPECIAL = 'RA 7610 (Special Protection of Child Against Abuse and Exploitation)';
    case ACCESS_DEVICE = 'RA 8484 (Access Device and Regulation Act) Ammended RA 11449';
    case CYBER_BULLYING = 'RA 10627 (Cyber Bullying)';
    case GRAVE_THREAT = 'Art. 282 of RPC (Grave Threat/Sextortion)';
    case GRAVE_COERCION = 'Art. 286 of RPC (Grave Coercion)';
    case ROBBERY = 'Art. 287 of RPC (Robbery Extortion)';
    case ONLINE_SCAM = 'Online Scam (Violation of Art. 315 of RPC in Rel. Sec. 6 of RA 10175)';
    case OSAEC = 'RA 11930 (OSAEC)';
}
