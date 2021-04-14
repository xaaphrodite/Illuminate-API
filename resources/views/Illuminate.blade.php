<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/illuminate.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
        integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w=="
        crossorigin="anonymous" />
    <link rel="icon" href="/assets/svg/gear.svg" type="image/icon">
    <title>Illuminate</title>
</head>
<body>
    <div class="container" id="illuminate">
        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="exampleModalLabel">Illuminate API <i class="fas fa-cogs"></i></h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container">
                            <h6>What is Illumininate API? It is an application programming interface between the <b>front end</b>
                                and the <b>back end</b>. You can freely use this public API for <b>training</b> on the front end or also
                                for <b>managing users</b> in your application. If you use this public API, you can also
                                access the database with owner permission. 
                                <a style="text-decoration: none" href="https://www.instagram.com/rasetiansyah_/">rasetiansyah</a></h6>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Table -->
        <div class="row">
            <div class="col">
                <h2>Illuminate Instructions API <i class="fas fa-cogs"></i></h2>
                <hr>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col"><i class="far fa-hdd"></i></th>
                            <th scope="col">API endpoint</th>
                            <th scope="col">?Parameter</th>
                            <th scope="col">Description</th>
                            <th scope="col">*Required</th>
                            <th scope="col">Token</th>
                            <th scope="col">Method</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">Register</th>
                            <td>/api/illuminate/portal</td>
                            <td>set</td>
                            <td></td>
                            <td>
                                <li>name</li>
                                <li>username</li>
                                <li>email</li>
                                <li>password</li>
                                <li>confirm_password</li>
                            </td>
                            <td></td>
                            <td id="method">POST</td>
                        </tr>
                        <tr>
                            <th scope="row">Login</th>
                            <td>/api/illuminate/portal</td>
                            <td>
                                <li>req</li>
                                <li style="color: red">wp</li>
                            </td>
                            <td style="font-size: 14px">
                                <li style="color: red">Getting permit</li>
                                <li style="color: red">*the value</li>
                                <li style="color: red">is user password</li>
                            </td>
                            <td>
                                <li>email</li>
                                <li>password</li>
                            </td>
                            <td></td>
                            <td id="method">POST</td>
                        </tr>
                        <tr>
                            <th scope="row">Logout</th>
                            <td>/api/illuminate/portal</td>
                            <td>
                                <li>out</li>
                                <li>id</li>
                            </td>
                            <td style="font-size: 14px">
                                <li>If the user logout</li>
                                <li>permit will be</li>
                                <li>changed</li>
                            </td>
                            <td>id</td>
                            <td></td>
                            <td id="method">POST</td>
                        </tr>
                        <tr>
                            <th scope="row">Generate Token</th>
                            <td>/api/illuminate/portal</td>
                            <td>Gkey</td>
                            <td>
                                <li>Tokens will expire</li>
                                <li>in 48 hours</li>
                            </td>
                            <td>
                                <li>username</li>
                                <li>password</li>
                            </td>
                            <td></td>
                            <td id="method">POST</td>
                        </tr>
                        <tr>
                            <th scope="row">Update</th>
                            <td>/api/illuminate/secure</td>
                            <td>
                                <li>up</li>
                                <li>p</li>
                            </td>
                            <td></td>
                            <td>
                                <li>name</li>
                                <li>username</li>
                                <li>email</li>
                                <li>permit</li>
                            </td>
                            <td><b>YES</b></td>
                            <td id="method">POST</td>
                        </tr>
                        <tr>
                            <th scope="row">Delete</th>
                            <td>/api/illuminate/secure</td>
                            <td>
                                <li>del</li>
                                <li>p</li>
                            </td>
                            <td></td>
                            <td>permit</td>
                            <td><b>YES</b></td>
                            <td id="method">POST</td>
                        </tr>
                        <tr>
                            <th scope="row">Show</th>
                            <td>/api/illuminate/secure</td>
                            <td>
                                <li>s</li>
                                <li>p</li>
                            </td>
                            <td></td>
                            <td>permit</td>
                            <td><b>YES</b></td>
                            <td id="method">GET</td>
                        </tr>
                        <tr>
                            <th scope="row">Revoke Token</th>
                            <td>/api/illuminate/secure</td>
                            <td>
                                <li>rv</li>
                                <li>apikey</li>
                            </td>
                            <td></td>
                            <td>
                                <li>revoke</li>
                                <li style="color:red">revoke_all</li>
                            </td>
                            <td><b>YES</b></td>
                            <td id="method">POST</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="container" style="margin-top: -25px">
        <p>*<span style="color:red">red</span> is optional</p>
        <hr>
        <footer><a href="https://github.com/xaaphrodite">rasetiansyah</a></footer>
        <div style="margin-top: -25px">
            <h5>--help</h5>
            <h6 style="list-style-type:none">
                <li><b>wp</b> : with permit</li>
                <li><b>s</b> : show</li>
                <li><b>p</b> : permit (25 random string)</li>
                <li><b>rv</b> : revoke</li>
                <li><b>apikey</b> : value is revoke or revoke_all</li>
                <li><b>revoke</b> : Current access token</li>
                <li><b>revoke_all</b> : All access token</li>
            </h6>
            <h5 id="ask" data-bs-toggle="modal" data-bs-target="#exampleModal">Click me! <i
                    class="far fa-question-circle"></i></h5>
            <hr>
            <p style="margin-top: -15px">endpoint protected by csrf</p>
        </div>
    </div>
    <script src="/js/illuminate.js"></script>
    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous">
    </script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js" integrity="sha384-SR1sx49pcuLnqZUnnPwx6FCym0wLsk5JZuNx2bPPENzswTNFaQU1RDvt3wT4gWFG" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.min.js" integrity="sha384-j0CNLUeiqtyaRmlzUHCPZ+Gy5fQu0dQ6eZ/xAww941Ai1SxSY+0EQqNXNE6DZiVc" crossorigin="anonymous"></script>
    -->
</body>

</html>
