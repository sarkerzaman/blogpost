<template>
    <div>
        <h2>Recent Posts</h2>  
        <form @submit.prevent="addPost" class="mb-3">
            <div class="form-group">
                <input v-model="post.title" type="text" class="form-control" placeholder="Post Title">                
            </div>
            <div class="form-group">
                <textarea v-model="post.body" cols="30" rows="4" class="form-control" placeholder="Post Body"></textarea>
            </div>
            <button type="submit" class="btn btn-secondary btn-block">Save</button>           
        </form>
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li v-bind:class="[{ disabled: !pagination.prev_page_url }]" class="page-item">
                    <a class="page-link" href="#" @click="fetchPosts(pagination.prev_page_url)">Previous</a>
                </li> 
                <li class="page-item disabled"><a class="page-link text-dark" href="#">
                    Page {{ pagination.current_page }} of {{ pagination.last_page }}</a>
                </li>             
                <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item">
                    <a class="page-link" href="#" @click="fetchPosts(pagination.next_page_url)">Next</a>
                </li>
            </ul>
        </nav>
        <div class="card card-body mb-2" v-for="post in posts" v-bind:key="post.id">
            <h3>{{ post.title }}</h3>
            <p>{{ post.body }}</p>
            <hr>
            <button class="btn btn-warning mb-2" @click="editPost(post)">Edit</button>
            <button class="btn btn-danger" @click="deletePost(post.id)">Delete</button>
        </div>     
    </div>
</template>

<script>
    export default {
        data(){
            return{
                posts:[],
                post:{
                    id:'',
                    title:'',
                    boby:''
                },
                post_id:'',
                pagination:{},
                edit:false
            };
        },
        created(){
            this.fetchPosts();
        },
        methods:{
            fetchPosts(page_url){
                let vm = this;
                page_url = page_url || "api/posts";
                fetch(page_url)
                    .then(res => res.json())
                    .then(res => {                         
                        //console.log(res.data);
                        this.posts = res.data;
                        vm.makePagination(res.meta, res.links);
                    })
                    .catch(err => console.log(err));
            },
            makePagination(meta, links){
                let pagination = {
                    current_page: meta.current_page,
                    last_page: meta.last_page,
                    next_page_url: links.next,
                    prev_page_url: links.prev
                };
                this.pagination = pagination;
            },
            deletePost(id){
                if(confirm('Are you sure?')){
                    fetch(`api/posts/${id}`, { method: 'delete' })
                        .then(res => res.json())
                        .then(data => {  
                            alert('Blog post removed');
                            this.fetchPosts();
                        })
                        .catch(err => console.log(err));
                }
            },
            addPost(){
                if(this.edit === false){
                    //Add
                    fetch('api/posts', {
                        method: 'post',
                        body: JSON.stringify(this.post),
                        headers:{
                            'content-type': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.post.title = '';
                        this.post.body = '';
                        alert('Post Added');
                        this.fetchPosts();
                    })
                    .catch(err => console.log(err));
                }else{
                    //Update
                    fetch('api/posts', {
                        method: 'put',
                        body: JSON.stringify(this.post),
                        headers:{
                            'content-type': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.post.title = '';
                        this.post.body = '';
                        alert('Post Updated');
                        this.fetchPosts();
                    })
                    .catch(err => console.log(err));
                }
            },
            editPost(post){
                this.edit = true;
                this.post.id = post.id;
                this.post.post_id = post.id;
                this.post.title = post.title;
                this.post.body = post.body;
            }
        }
    };
</script>
