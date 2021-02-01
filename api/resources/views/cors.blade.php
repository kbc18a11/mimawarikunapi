<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <title>Document</title>
</head>

<body>
    <script>
        async function getRoomInfo(url = '') {
            let headers = {
                'Content-Type': 'application/json',
                token: 'W3F9gkQpgaRjNairZdToCugR4KtydOLmzVQfbOwqFiuoRpwqAY1RSflIAMRM'
            };
            try {
                const response = await (await fetch(url, {
                    mode: 'no-cors',
                    headers: headers
                })).text();
            } catch (error) {
                console.error('Error:', error)
            }
        }

        function setRoomInfo() {
            getRoomInfo('http://127.0.0.1:8085/api/room')
            /*.then(res => res.json())
            .then((json)=>{
                this.setState({roomInfo:json})
            })
            .catch(error => {
                console.error('Error:', error)
              })
              */
        }

        window.onload = () => {
            setRoomInfo();
            const url = 'http://127.0.0.1:8085/api/room';
            let headers = {
                'Content-Type': 'application/json',
                token: 'W3F9gkQpgaRjNairZdToCugR4KtydOLmzVQfbOwqFiuoRpwqAY1RSflIAMRM'
            };
            fetch('http://127.0.0.1:8085/api/room', {
                    headers: headers
                })
                .then((res) => {

                    console.log(res);
                    res.json();
                })
                .then((json) => {
                    console.log(json);
                })
                .catch(error => {
                    console.error('Error:', error);
                });


            axios.defaults.headers.common = {
                token: 'W3F9gkQpgaRjNairZdToCugR4KtydOLmzVQfbOwqFiuoRpwqAY1RSflIAMRM'
            };
            axios.get(url)
                .then(function(response) {

                    console.log(response);
                })
                .catch(function(error) {
                    console.log(error);
                    console.log(error.response);
                });
        };
    </script>
</body>

</html>
