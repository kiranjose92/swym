# Instrument as a Service

It is a an API, build using PHP, which accepts array of note  arrangements and returns the audio file merged with those notes.

Currently the API supports only the following piano notes:
```
'A', 'B', 'B#', 'C', 'C#', 'D', 'D#', 'E', 'F', 'F#','G', 'G#'
```


### Implementation
The notes arrangement format would be an array of notes with each note/sequence prefixed by the repetition count. For example (Assume all elements in the array are String)
If your API is called with [2C, E#, D, 3DEC], It should return a wav/mp3 file or a link to the file which plays the notes as per the input. ie. 
2C-C note played twice, 
E# - E# played once, 
D - D once and then
3DEC  - plays (D followed by E followed by C) - 3 times. 

Another example: [3D, 3C4E], it should play 3D-D thrice. 
3C4E- C once and E 4 times and repeat this 3 times. Same as [C, E, E, E, E, C, E, E, E, E, C, E, E, E, E]

The prefix is guaranteed to be a single digit. So if you get [33C] - it means C is played three times and then repeated 3 times - playing a total of 9 times instead of 33 times. 

If you get [333C] - it means C is played 33 times and then repeated 3 times - playing a total of 99 times. 

Similarly [33C3D] - means play C thrice, D thrice and repeat this thrice. 


### Prerequisites
Need to install the following to configure the application:
* PHP >= 7.1
* [wavmerge](http://manpages.ubuntu.com/manpages/impish/man1/wavmerge.1.html) command line tool. use the following command to install it in Ubuntu:
 ```
  sudo apt install wavbreaker
 ```
* Apache or NGINX webserver to host the application

### API
Detaiils of the API that takes in notes arrangement and returns a file with those notes played:

**Endpoint:** `/notetomusic.php`

**HTTP Method:** `POST`

**Request Header:** `Content-Type:application/json`

**Sample Payload:** 
```
{
    "notes": ["2C", "B#",  "A"]
}
```


**The application is hosted in Heroku - https://pure-river-31945.herokuapp.com and the API URL is:** `https://pure-river-31945.herokuapp.com/notetomusic.php`