<!DOCTYPE html>
<html>
<head>
    <title>Numbers to Words Calculator</title>
</head>
<body>
    <h2>NUMBERS TO WORDS CALCULATOR</h2>
    <form method="post" action="">
        <label>Please input your data (Riel):</label>
        <input type="text" name="riel" required>
        <input type="submit" name="submit" value="Convert">
    </form>

<?php
function convertNumberToWordsEN($num) {
    $ones = ["", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine",
             "Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen",
             "Seventeen", "Eighteen", "Nineteen"];
    $tens = ["", "", "Twenty", "Thirty", "Forty", "Fifty", "Sixty", "Seventy", "Eighty", "Ninety"];
    $aboveThousand = ["", "Thousand", "Million", "Billion"];

    if ($num == 0) return "Zero";

    $words = "";
    $place = 0;

    while ($num > 0) {
        $chunk = $num % 1000;
        if ($chunk > 0) {
            $chunkWords = "";

            if ($chunk > 99) {
                $chunkWords .= $ones[intval($chunk / 100)] . " Hundred ";
                $chunk %= 100;
            }
            if ($chunk > 0) {
                if ($chunk < 20) {
                    $chunkWords .= $ones[$chunk] . " ";
                } else {
                    $chunkWords .= $tens[intval($chunk / 10)] . " ";
                    $chunkWords .= $ones[$chunk % 10] . " ";
                }
            }

            $words = $chunkWords . $aboveThousand[$place] . " " . $words;
        }
        $num = intval($num / 1000);
        $place++;
    }

    return trim($words);
}

// Improved Khmer number translation
function convertToKhmerWords($number) {
    if (!is_numeric($number) || $number < 0) return "លេខមិនត្រឹមត្រូវ";
    if ($number == 0) return "សូន្យ រៀល";

    $khmerDigits = ["", "មួយ", "ពីរ", "បី", "បួន", "ប្រាំ", "ប្រាំមួយ", "ប្រាំពីរ", "ប្រាំបី", "ប្រាំបួន"];
    $units = ["", "ដប់", "រយ", "ពាន់", "ម៉ឺន", "សែន", "លាន"];
    $numStr = strrev((string)$number);
    $khmerWord = "";
    for ($i = 0; $i < strlen($numStr); $i++) {
        $digit = (int)$numStr[$i];
        if ($digit == 0) continue;
        $khmerDigit = isset($khmerDigits[$digit]) ? $khmerDigits[$digit] : "";
        $unit = isset($units[$i]) && $units[$i] != "" ? " " . $units[$i] : "";
        $word = $khmerDigit . $unit;
        $khmerWord = $word . " " . $khmerWord;
    }
    return trim($khmerWord) . " រៀល";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $riel = (int)$_POST["riel"];
    $usd = number_format($riel / 4000, 2);
    $englishWords = convertNumberToWordsEN($riel) . " Riel";
    $khmerWords = convertToKhmerWords($riel);

    // Display results
    echo "<h3>Results:</h3>";
    echo "<p>a. $englishWords</p>";
    echo "<p>b. $khmerWords</p>";
    echo "<p>c. $usd USD</p>";

    // Save to text file
    $log = "Input: $riel Riels | English: $englishWords | Khmer: $khmerWords | USD: $usd $\n";
    file_put_contents("conversion_log.txt", $log, FILE_APPEND);

    // Save to file
    $entry = "Riel: $riel | English: $englishWords | Khmer: $khmerWords | USD: $usd\n";
    file_put_contents("current_projects.txt", $entry, FILE_APPEND);
}
?>
</body>
</html>
