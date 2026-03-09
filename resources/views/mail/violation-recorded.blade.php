<!DOCTYPE html>
<html>

    <body class="bg-transparent">

        <p>Greetings of Peace!</p>

        <p>Dear {{ $violation->student_name }},</p>

        <p>
            This is to inform you that you have been recorded for
            a <strong>({{ $violation->violation_type_code_snapshot }} {{ $violation->violation_type_name_snapshot }})</strong>
            under the AdZU policy
            on {{ $violation->created_at->format('F d, Y') }}.
        </p>

        @if (!empty($violation->violation_remark_snapshot) && $violation->violation_remark_snapshot !== 'N/A')
            <p>
                <strong>Details:</strong> {{ $violation->violation_remark_snapshot }}
            </p>
        @endif

        <p>
            In line with this, you are required to <strong>report to the Office of Student Affairs</strong>
            at your earliest convenience to undergo the necessary processing of the corresponding consequences.
        </p>

        <p>
            Please treat this matter with urgency. Failure to comply may lead to further disciplinary action.
        </p>

        <p>
            Should you have questions, you may reach us through local no. 2204-2205 or visit me in the Office of Student
            Affairs.
        </p>

        <p>
            Thank you for your cooperation.
        </p>

        <br>

        <p>
            <strong>Program Officer for Student Discipline (OSA)</strong><br>
            Office of Student Affairs
        </p>
    </body>

</html>
