<!DOCTYPE html>
<html>

    <body class="bg-transparent">

        <p>Greetings of Peace!</p>

        <p>Dear {{ $violation->st_first_name }} {{ $violation->mi ? $violation->mi . '.' : '' }}
            {{ $violation->st_last_name }},</p>

        <p>
            This is to inform you that you have been recorded for
            a <strong>({{ $violation->type_code }} {{ $violation->type_name }})</strong>
            under the AdZU policy
            on {{ $violation->created_at->format('F d, Y h:i:s A') }}
        </p>

        @if ($violation->remark)
            <p>
                <strong>Remarks:</strong> {{ $violation->remark }}
            </p>
        @endif

        @if ($violation->is_escalated)
            <p>
                <strong>Note:</strong> This violation is has been escalated to a <strong>Major Offense</strong> and
                requires immediate attention.
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
            Should you have questions, you may reach us through local no. 2204-2205 or visit the Office of Student
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
