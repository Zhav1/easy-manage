<table>
    {{-- This loop will iterate through all 13 forms passed from the export class --}}
    @foreach($reportData as $formType => $formReport)
        @php
            // A simple way to get a reasonable column span for the title
            $colCount = 20; 
        @endphp
        
        {{-- Section Title Row --}}
        <tr>
            <th colspan="{{ $colCount }}" style="font-size: 14px; font-weight: bold; background-color: #b0c4de; text-align: center; border: 1px solid #000000;">
                {{ strtoupper($formReport['name']) }}
            </th>
        </tr>

        {{-- Table Headers for this specific form --}}
        @includeFirst(['reports.tables.headers.' . $formType, 'reports.tables.headers.default'])

        {{-- Table Body with all the data for this specific form --}}
        @includeFirst(['reports.tables.' . $formType, 'reports.tables.default-data'], ['data' => $formReport['data']])

        {{-- Spacer Rows for readability --}}
        <tr></tr>
        <tr></tr>
    @endforeach
</table>