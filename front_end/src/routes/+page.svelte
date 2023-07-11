<script lang="ts">
    import { BASE_URL } from "./api";
    import axios from "axios"
    import { goto } from "$app/navigation";

  import { onMount } from "svelte";
    console.log(BASE_URL)

    let email:String
    let password:String
    let validation:Boolean = false

    let btn:HTMLButtonElement

    async function doLogin() {
        btn.disabled = true
        await axios.post(`${BASE_URL}/v1/auth/login`, {
            email: email,
            password: password
        }).then((res) => {
            validation = false
            console.log(res)
            localStorage.setItem("user", JSON.stringify(res.data.Body))
            goto("/dashboard")
        }).catch((err) => {
            btn.disabled = false
            validation = true
            console.log(err)
        })
    }

    onMount(() => {
        let user = JSON.parse(localStorage.getItem("user") as string);
        console.log(user)   


        if(user != null) {
            goto("/dashboard")
        } 
    })
</script>

<svelte:head>
    <title>Login</title>
</svelte:head>

<main>
    <div class="d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="card">
            <div class="card-body">
                <h3 class="text-center my-5">LOGIN</h3>
                {#if validation == true}
                     <div class="alert alert-danger">
                         Email or password incorrect
                    </div>
                {/if}
                <form on:submit|preventDefault={doLogin}>
                    <div class="mb-3">
                      <label for="exampleInputEmail1" class="form-label">Email address</label>
                      <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" bind:value={email}>
                      <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                    </div>
                    <div class="mb-3">
                      <label for="exampleInputPassword1" class="form-label">Password</label>
                      <input type="password" bind:value={password} class="form-control" id="exampleInputPassword1">
                    </div>
                    <div class="mb-3 form-check">
                      <input type="checkbox" class="form-check-input" id="exampleCheck1">
                      <label class="form-check-label" for="exampleCheck1">Check me out</label>
                    </div>
                    <button bind:this={btn} type="submit" style="width: 100%;" class="btn btn-primary">LOGIN</button>
                  </form>
            </div>
        </div>
    </div>
</main>
