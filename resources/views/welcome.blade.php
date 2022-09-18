<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laravel Api CRUD Axios</title>

    <!--Bootstrap CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
</head>
<body>
    
    <div class="container pt-5">
        <div class="row">
            <div class="col-md-8">
                
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Posts</h3>

                        <span id="successMsg"></span>
                        
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Post Creation Form</h5>
                        <span id="successMsg">

                        </span>
                        <form name="myForm">
                            <div class="mb-3">
                                <label>Title</label>
                                <input type="text" name="title" class="form-control">
                                <span id="nameErr">

                                </span>
                            </div>
                            <div class="mb-3">
                                <label>Description</label>
                                <textarea name="description" rows="4" class="form-control"></textarea>
                                <span id="descErr">

                                </span>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Post Edit modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Post Edition</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form name="editForm">
                 <div class="modal-body">
                    <div class="mb-3">
                        <label>Title</label>
                        <input type="text" name="title" class="form-control">
                        <span id="nameErr">

                        </span>
                    </div>
                    <div class="mb-3">
                        <label>Description</label>
                        <textarea name="description" rows="4" class="form-control"></textarea>
                        <span id="descErr">

                        </span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal">Save changes</button>
                </div>
            </form>
        </div>
        </div>
    </div>

    <!--Bootstrap JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>

    <!--AXIOS LINK -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        // Read
        var tableBody = document.getElementById('tableBody');
        var titleList = document.getElementsByClassName('titleList');
        var descList = document.getElementsByClassName('descList');
        var tableData = document.getElementsByClassName('tableData');
        var i = 1;
        axios.get('api/posts')
             .then( response => {
                 response.data.forEach( item => {
                     tableBody.innerHTML += `
                     <tr class="tableData">
                        <td>${i}</td>
                        <td class="titleList">${item.title}</td>
                        <td class="descList">${item.description}</td>
                        <td>
                            <a href="" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal" onclick="editBtn(${item.id})">Edit</a>
                            
                            
                            <button class="btn btn-danger btn-sm" onclick="deleteBtn(${item.id})">Delete</button>
                            </td>
                            </tr>
                            `
                            i++;
                })
             } )
             .catch( error => console.log(error) );
            
        // Create
        var myForm = document.forms['myForm'];
        var titleInput = myForm['title'];
        var descriptionInput = myForm['description'];

        myForm.onsubmit = function(e){
            e.preventDefault();
            
            axios.post('/api/posts', {
                title: titleInput.value,
                description: descriptionInput.value
            })
            .then( response => {
                var titleErr = document.getElementById('nameErr');
                var descErr = document.getElementById('descErr');
                if (response.data.msg == "Created successfully!") {
                    document.getElementById('successMsg').innerHTML = `
                    <div class="alert alert-success" role="alert">
                        ${response.data.msg}
                    </div>
                    `;
                    console.log('before: '+ i);
                    var id = ++i;
                    console.log('after: '+ i);
                    tableBody.innerHTML += `
                        <tr>
                            <td>${id-1}</td>
                            <td>${response.data[0].title}</td>
                            <td>${response.data[0].description}</td>
                            <td>
                                <a href="" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal" onclick="editBtn(${response.data[0].id})">Edit</a>

                                
                                <button class="btn btn-danger btn-sm" onclick="deleteBtn(${response.data[0].id})">Delete</button>
                            </td>
                        </tr>
                    `
                    myForm.reset();
                    titleErr.innerHTML = descErr.innerHTML = '';
                } else {

                    titleErr.innerHTML = titleInput.value == "" ? `<p class="text-danger">${response.data.msg.title}</p>` : '';

                    descErr.innerHTML = descriptionInput.value == "" ? `<p class="text-danger">${response.data.msg.description}</p>` : '';
                }
            } )
            .catch( err => {
                console.log(err);
            })
        }

        // Edit & Update
        var editForm = document.forms['editForm'];
        var editTitleInput = editForm['title'];
        var editDescInput = editForm['description'];
        var idForUpdate;
        
        // Edit
        function editBtn(postId){
            idForUpdate = postId;
            axios.get('api/posts/'+postId)
            .then( response => {
                editTitleInput.value = response.data.title;
                editDescInput.value = response.data.description;

                oldTitle = response.data.title;
                oldDesc = response.data.description;
            })
            .catch(err => console.log(err))
        }

        // Update
        editForm.onsubmit = function(e) {
            e.preventDefault();
            axios.put('api/posts/'+idForUpdate, {
                title : editTitleInput.value,
                description : editDescInput.value
            })
            .then( response => {
                for(let i = 0; i < titleList.length; i++){
                    if(titleList[i].innerHTML == oldTitle){
                        titleList[i].innerHTML = editTitleInput.value;
                    }
                    if(descList[i].innerHTML == oldDesc){
                        descList[i].innerHTML = editDescInput.value;
                    }
                }
                document.getElementById('successMsg').innerHTML = `
                <div class="alert alert-success" role="alert">
                    ${response.data.msg}
                </div>`;
                var myModal = new bootstrap.Modal(document.getElementById('editModal'), hide);

            })
            .catch(err => console.log(err))
        }

        // Delete
        function deleteBtn(deleteId){
            if(confirm('Are you sure you want to delete!')){
                axios.delete('api/posts/'+deleteId)
            .then( response => {
                document.getElementById('successMsg').innerHTML = `
                <div class="alert alert-danger" role="alert">
                    ${response.data.msg}
                </div>`;
                for(let i = 0; i < tableData.length; i++){
                    if(titleList[i].innerHTML == response.data.deletedPost.title){
                        tableData[i].style.display = 'none';
                    }
                }
            })
            .catch(err => console.log(err))
            }
        }

    </script>
</body>
</html>