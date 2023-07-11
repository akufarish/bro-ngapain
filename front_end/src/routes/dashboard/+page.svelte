<script lang="ts">
  import { goto } from "$app/navigation";
  import axios from "axios";
  import { afterUpdate, onMount } from "svelte";
  import { BASE_URL } from "../api";


    interface post {
        thread: String,
        slug: String,
        created_at: String
        id: number,
    }

    interface dataUser {
        user: any,
        token: string
    }


    let thread:String
    let slug:String
    let users: dataUser
    let btn: HTMLButtonElement

    let dataPostingan:Array<post> = []

        async function createPost() {
            await axios.post(`${BASE_URL}/v1/post?token=${users.user.token}`, {
                thread: thread,
                slug: slug
            }).then((res) => {
                console.log(res)
                console.log(res.data)
                btn.disabled = true
                goto("/dashboard")
            }).catch((err) => {
                console.log(err)
                btn.disabled = false
            })
    }
    


    onMount(() => {
        let user = JSON.parse(localStorage.getItem("user") as string);
        console.log(user)
        console.log(user.user.token)

        if(user == null) {
            goto("/")
        } 
        async function getPost() {            
            let dataPost:Array<post> = []
            await axios.get(`${BASE_URL}/v1/post?token=${user.user.token}`)
            .then((res) => {
                console.log(res)
                console.log(res.data.Body.post)
                dataPost = res.data.Body.post
                console.log(dataPost)
            }).catch((err) => {
                console.log(err)
            })
            users = user
            dataPostingan = dataPost
            console.log(users)
        }

        return [getPost()]
    })

</script>


<svelte:head>
    <title>dashboard</title>
</svelte:head>

<main>
    <div class="d-flex justify-content-center align-items-center" style="flex-direction: column; gap: 10px;" >
        <div class="card">
            <div class="card-body">
                        <!-- Button trigger modal -->
            <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#exampleModal">
                + Tambah postingan
            </button>
            
            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form on:submit|preventDefault={createPost}>
                            <div class="mb-3">
                              <label for="exampleInputEmail1" class="form-label">Threads</label>
                              <input type="text" bind:value={thread} class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                            </div>
                            <div class="mb-3">
                              <label for="exampleInputPassword1" class="form-label">Slug</label>
                              <input type="text" bind:value={slug} class="form-control" id="exampleInputPassword1">
                            </div>
                            <button type="submit" style="width: 100%;" bind:this={btn} class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
                </div>
            </div>
            </div>
        </div>

        {#each dataPostingan as data}
             <div class="card">
                 <div class="card-body">
                    <p>{data.thread}</p>
                    <p>{data.created_at}</p>
                </div>
            </div>
        {/each}
    </div>
</main>