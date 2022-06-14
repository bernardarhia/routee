<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<pre>

</pre>
<br />

<body>
    <input type="text">
    <button>submit</button>
    <script>
    const input = document.querySelector("input")
    const button = document.querySelector("button")

    async function sendRequest() {
        const data = {
            name: input.value
        }

        try {
            const response = await fetch("/session", {
                method: "POST",
                body: JSON.stringify(data),
            })
            const result = await response.json()
            console.log(result)
        } catch (e) {
            console.log(e);
        }

    }
    button.addEventListener("click", sendRequest)
    </script>
</body>

</html>