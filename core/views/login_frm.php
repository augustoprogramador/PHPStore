<div class="container">
    <div class="row my-5">
        <div class="col-sm-6 offset-sm-3">
        
            <div>
                <h3 class="text-center">Login!</h3>

                <form action="?a=login_submit" method="post">
                    <div class="my-3">
                        <label">Usuário:</label>
                        <input class="form-control" type="email" name="text_usuario" placeholder="Email" required>
                    </div>

                    <div class="my-3">
                        <label">Senha:</label>
                        <input class="form-control" type="password" name="text_password" placeholder="Senha" required>
                    </div>
                    
                    <div class="my-3 text-center">
                        <input class="btn btn-primary" type="submit" value="Entrar">
                    </div>
                </form>

                <?php if(isset($_SESSION['erro'])): ?>
                    <div class="alert alert-danger text-center">
                        <?= $_SESSION['erro']; ?>
                        <?php unset($_SESSION['erro']); ?>
                    </div>
                <?php endif; ?>

            </div>

        </div>
    </div>
</div>