<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ViolationType;

class ViolationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $violations = [
            // MINOR VIOLATIONS
            ['code' => 'C.1.1', 'name' => 'No ID or improper display of ID', 'classification' => 'Minor'],
            ['code' => 'C.1.2', 'name' => 'Disruption of classes or any academic activity or school function', 'classification' => 'Minor'],
            ['code' => 'C.1.3', 'name' => 'Bringing of vape inside the campus', 'classification' => 'Minor'],
            ['code' => 'C.1.4', 'name' => 'Smoking or vaping inside the school premises', 'classification' => 'Minor'],
            ['code' => 'C.1.5', 'name' => 'Intoxication or being under the influence of liquor or prohibited substances', 'classification' => 'Minor'],
            ['code' => 'C.1.6', 'name' => 'Possession of alcoholic beverages and e-cigarettes (vape) on campus', 'classification' => 'Minor'],
            ['code' => 'C.1.7', 'name' => 'Misuse of University Facilities', 'classification' => 'Minor'],
            ['code' => 'C.1.8', 'name' => 'Use of obscene or vulgar language in person, online, or in any form of communication', 'classification' => 'Minor'],
            ['code' => 'C.1.9', 'name' => 'Littering (Plus P100 fine)', 'classification' => 'Minor'],
            ['code' => 'C.1.10', 'name' => 'Bringing in Styrofoam (Plus P500 fine)', 'classification' => 'Minor'],
            ['code' => 'C.1.11', 'name' => 'Tampering with electrical switches and other University fixtures or gadgets', 'classification' => 'Minor'],
            ['code' => 'C.1.12', 'name' => 'Public display of intimacy and other such acts that offend the sensibilities of the community', 'classification' => 'Minor'],
            ['code' => 'C.1.13', 'name' => 'Use of classroom and other school facilities without reservation or permission', 'classification' => 'Minor'],
            ['code' => 'C.1.14', 'name' => 'Eating in the Laboratories', 'classification' => 'Minor'],

            // MAJOR VIOLATIONS - SUSPENSION
            ['code' => 'C.3.1', 'name' => 'Any form of cheating or academic dishonesty on an examination', 'classification' => 'Major - Suspension'],
            ['code' => 'C.3.2', 'name' => 'Fraud or use of fabricated/altered data or possession of leaked examination papers', 'classification' => 'Major - Suspension'],
            ['code' => 'C.3.3', 'name' => 'False representation in an examination or completing assessment for another person', 'classification' => 'Major - Suspension'],
            ['code' => 'C.3.4', 'name' => 'Erasing, removing, tampering with, or destroying official notices and posters', 'classification' => 'Major - Suspension'],
            ['code' => 'C.3.5', 'name' => 'Disrespect to a teacher, other university personnel, or fellow student', 'classification' => 'Major - Suspension'],
            ['code' => 'C.3.6', 'name' => 'Any form of vandalism (writing/drawing on walls, furniture, books, etc.)', 'classification' => 'Major - Suspension'],
            ['code' => 'C.3.7', 'name' => 'Any form of gambling on campus or at off-campus university functions', 'classification' => 'Major - Suspension'],
            ['code' => 'C.3.8', 'name' => 'IT misuse (unauthorized use, altering information, damaging data, etc.)', 'classification' => 'Major - Suspension'],
            ['code' => 'C.3.9', 'name' => 'Commission of a fourth minor violation', 'classification' => 'Major - Suspension'],
            ['code' => 'C.3.10', 'name' => 'Disrespect to teacher or university personnel (in person, online, or any communication)', 'classification' => 'Major - Suspension'],
            ['code' => 'C.3.11', 'name' => 'Coming onto campus under the influence of alcohol', 'classification' => 'Major - Suspension'],

            // MAJOR VIOLATIONS - DISMISSAL
            ['code' => 'C.3.12', 'name' => 'Bribery or offering inducements to influence assessment or grades', 'classification' => 'Major - Dismissal'],
            ['code' => 'C.3.13', 'name' => 'Intentionally making false statement or fraudulent act related to University', 'classification' => 'Major - Dismissal'],
            ['code' => 'C.3.14', 'name' => 'Unauthorized solicitation or collection of money or instruments', 'classification' => 'Major - Dismissal'],
            ['code' => 'C.3.15', 'name' => 'Misuse of university/student funds', 'classification' => 'Major - Dismissal'],
            ['code' => 'C.3.16', 'name' => 'Borrowing, lending, or using another person\'s ID / Tampering or use of fake ID', 'classification' => 'Major - Dismissal'],
            ['code' => 'C.3.17', 'name' => 'Forging or tampering with official university records or transfer forms', 'classification' => 'Major - Dismissal'],
            ['code' => 'C.3.18', 'name' => 'Plagiarism or using another person\'s work without acknowledgment', 'classification' => 'Major - Dismissal'],
            ['code' => 'C.3.19', 'name' => 'Instigating, leading, or participating in unlawful activity disrupting classes', 'classification' => 'Major - Dismissal'],
            ['code' => 'C.3.20', 'name' => 'Criminal act proven in court', 'classification' => 'Major - Dismissal'],
            ['code' => 'C.3.21', 'name' => 'Possession or use of firecrackers and other dangerous compounds on campus', 'classification' => 'Major - Dismissal'],
            ['code' => 'C.3.22', 'name' => 'Participation in scandalous or immoral acts causing ill-repute to University', 'classification' => 'Major - Dismissal'],
            ['code' => 'C.3.23', 'name' => 'Possession or distribution of pornography and related materials', 'classification' => 'Major - Dismissal'],
            ['code' => 'C.3.24', 'name' => 'Prostitution or involvement in sexual activity for payment', 'classification' => 'Major - Dismissal'],
            ['code' => 'C.3.25', 'name' => 'Misrepresentation or unauthorized use of Ateneo de Zamboanga University name', 'classification' => 'Major - Dismissal'],
            ['code' => 'C.3.26', 'name' => 'Theft, Pilferage, and/or robbery of any form', 'classification' => 'Major - Dismissal'],
            ['code' => 'C.3.27', 'name' => 'Data privacy violation (stealing or attempting to steal another person\'s data)', 'classification' => 'Major - Dismissal'],
            ['code' => 'C.3.28', 'name' => 'Physical assault/verbal assault/provocation', 'classification' => 'Major - Dismissal'],
            ['code' => 'C.3.29', 'name' => 'Fighting or any form of violence on campus or at university functions', 'classification' => 'Major - Dismissal'],
            ['code' => 'C.3.30', 'name' => 'Assault on or threats to teacher and other university personnel', 'classification' => 'Major - Dismissal'],
            ['code' => 'C.3.31', 'name' => 'Bullying (using any means to intimidate a student or community member)', 'classification' => 'Major - Dismissal'],
            ['code' => 'C.3.32', 'name' => 'Participating in any action degrading University\'s IT performance', 'classification' => 'Major - Dismissal'],
            ['code' => 'C.3.33', 'name' => 'Membership in subversive organizations or those inconsistent with University values', 'classification' => 'Major - Dismissal'],
            ['code' => 'C.3.34', 'name' => 'Membership in Greek-lettered organizations or similar societies', 'classification' => 'Major - Dismissal'],
            ['code' => 'C.3.35', 'name' => 'Sexual Harassment', 'classification' => 'Major - Dismissal'],
            ['code' => 'C.3.36', 'name' => 'Extortion', 'classification' => 'Major - Dismissal'],
            ['code' => 'C.3.37', 'name' => 'Cyberbullying', 'classification' => 'Major - Dismissal'],
            ['code' => 'C.3.38', 'name' => 'Coming onto campus under the influence of prohibited substances', 'classification' => 'Major - Dismissal'],

            // MAJOR VIOLATIONS - EXPULSION
            ['code' => 'C.3.39', 'name' => 'Involvement in terrorism or radical extremism', 'classification' => 'Major - Expulsion'],
            ['code' => 'C.3.40', 'name' => 'Possession or use of deadly weapons and explosives', 'classification' => 'Major - Expulsion'],
            ['code' => 'C.3.41', 'name' => 'Hazing or any act of initiation rites that injures, degrades, or harms', 'classification' => 'Major - Expulsion'],
            ['code' => 'C.3.42', 'name' => 'Threatening someone with infliction upon person, honor, or property', 'classification' => 'Major - Expulsion'],
            ['code' => 'C.3.43', 'name' => 'Misuse/abuse of IT resources or accessing university systems without authorization', 'classification' => 'Major - Expulsion'],
            ['code' => 'C.3.44', 'name' => 'Engaging in scandalous/immoral acts causing dishonor to University', 'classification' => 'Major - Expulsion'],
            ['code' => 'C.3.45', 'name' => 'Engaging in subversive acts as defined by national laws', 'classification' => 'Major - Expulsion'],
            ['code' => 'C.3.46', 'name' => 'Possessing, distributing, or using leaked examination papers and questions', 'classification' => 'Major - Expulsion'],
            ['code' => 'C.3.47', 'name' => 'Engaging in hooliganism', 'classification' => 'Major - Expulsion'],
            ['code' => 'C.3.48', 'name' => 'Threatening/assaulting a teacher and other university personnel', 'classification' => 'Major - Expulsion'],
            ['code' => 'C.3.49', 'name' => 'Instigating, leading, or participating in unlawful activity stopping classes', 'classification' => 'Major - Expulsion'],
            ['code' => 'C.3.50', 'name' => 'Unlawfully preventing faculty, personnel, or students from attending classes', 'classification' => 'Major - Expulsion'],
            ['code' => 'C.3.51', 'name' => 'Forging/tampering with university records or using altered transfer forms', 'classification' => 'Major - Expulsion'],
            ['code' => 'C.3.52', 'name' => 'Fraud or use of fabricated/altered data in assessment items', 'classification' => 'Major - Expulsion'],
            ['code' => 'C.3.53', 'name' => 'Bribery to influence assessment outcome or subject grade', 'classification' => 'Major - Expulsion'],
            ['code' => 'C.3.54', 'name' => 'Possession, use, or distribution of prohibited or dangerous drugs', 'classification' => 'Major - Expulsion'],
        ];

        foreach ($violations as $violation) {
            ViolationType::create($violation);
        }
    }
}
