<?php

    const NOTES_FOLDER = 'piano-notes';

    // Get the notes arrangement input from the API request
    $notesArrangement = json_decode(file_get_contents('php://input'), true)['notes'];

    /*
     * Function to append $noteFileSet string with note file names
     * 
     * @param $noteFile sting
     * @param $repeat number
     * @param $noteFileSet string
     * @return $noteFileSet string
     */
    function setNoteFilesToMerge($noteFile, $repeat, $noteFileSet) {        
        if ($repeat) {
            $noteFileSet .= str_repeat($noteFile, $repeat);
        } else {
            $noteFileSet .= $noteFile;
        }
        return $noteFileSet;
    }

    // The variable to hold the note files in the order to be merged
    $wholeNoteFiles = '';
    foreach ($notesArrangement as $set) {
        $set = str_split($set);
        $repeatWhole = false;
        if (is_numeric($set[0])) {
            $repeatWhole = array_shift($set);
        }
        $noteFile = $repeat = $noteFileSet = '';
        foreach($set as $index => $note) {
            if (is_numeric($note)) {
                $repeat .= $note;
            } else {
                $noteFile .= $note;

                // Continue the iteration to get the full note name if the next character is '#'
                if (isset($set[$index + 1]) && $set[$index + 1] == '#') {
                    continue;
                }

                // validate that it is a supported note and return error
                if (!in_array($noteFile, ['A', 'B', 'B#', 'C', 'C#', 'D', 'D#', 'E', 'F', 'F#','G', 'G#'])) {
                    http_response_code(400);
                    exit('The request has unsupported note - ' . $noteFile);
                }

                $noteFile = $noteFile . '.wav ';
                $noteFileSet = setNoteFilesToMerge($noteFile, $repeat, $noteFileSet);
                $noteFile = $repeat = '';
            }
        }
        $wholeNoteFiles = setNoteFilesToMerge($noteFileSet, $repeatWhole, $wholeNoteFiles);
    }

    // Name of the final audio file
    $outputFile = time() . '-merged.wav';
    
    // Run the shell command to merge audio files of the full note set
    shell_exec('cd ' . getcwd() . '/' . NOTES_FOLDER .'; wavmerge -o  ' . $outputFile . ' ' . $wholeNoteFiles);

    $track = getcwd() . '/' . NOTES_FOLDER . '/' . $outputFile;
    
    // Return the audio file
    header('Content-type: ' . mime_content_type($track));
    header('Content-length: ' . filesize($track));
    header('Content-Disposition: filename="'.$outputFile.'"');
    header('X-Pad: avoid browser bug');
    header('Cache-Control: no-cache');
    readfile($track);

