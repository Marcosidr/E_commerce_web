<?php /** @var string $title */ ?>

<div class="contact-wrapper">
    <section class="py-5">
        <div class="container">
            <header class="contact-header" style="margin-bottom:3rem;">
                <h1 class="contact-header-title">Contato</h1>
                <p class="contact-lead">Tem alguma dúvida ou precisa de ajuda? Nossa equipe responde rápido. Preencha o formulário ou use um dos canais abaixo.</p>
            </header>

            <?php
            $success = $success ?? false;
            $errors = $errors ?? [];
            if ($success): ?>
                <div style="background:linear-gradient(90deg,#16a34a,#15803d); padding:1rem 1.25rem; border-radius:.75rem; margin-bottom:2rem; font-weight:600;">Mensagem enviada com sucesso! Em breve entraremos em contato.</div>
            <?php elseif(!empty($errors)): ?>
                <div style="background:#1a1a1a; border:1px solid #7f1d1d; padding:1rem 1.25rem; border-radius:.75rem; margin-bottom:2rem;">
                    <strong style="display:block; color:#f87171; margin-bottom:.5rem;">Corrija os erros:</strong>
                    <ul style="margin:0; padding-left:1.2rem;">
                        <?php foreach ($errors as $e): ?><li style="color:#fca5a5; font-size:.9rem;"><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="contact-grid">
                <!-- Formulário -->
                <div>
                    <h2 class="contact-form-title" style="font-size:1.9rem; font-weight:800; text-transform:uppercase; margin:0 0 1.75rem; letter-spacing:.05em;">Envie uma Mensagem</h2>
                    <form method="post" novalidate class="contact-form">
                        <div>
                            <label>Nome</label>
                            <input class="contact-input" name="name" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" />
                        </div>
                        <div>
                            <label>E-mail</label>
                            <input class="contact-input" type="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" />
                        </div>
                        <div>
                            <label>Assunto</label>
                            <select class="contact-select" name="subject" required>
                                <option value="">Selecione um assunto</option>
                                <option value="duvidas" <?= (($_POST['subject'] ?? '')==='duvidas')?'selected':''; ?>>Dúvidas sobre produtos</option>
                                <option value="pedido" <?= (($_POST['subject'] ?? '')==='pedido')?'selected':''; ?>>Status do pedido</option>
                                <option value="troca" <?= (($_POST['subject'] ?? '')==='troca')?'selected':''; ?>>Trocas e devoluções</option>
                                <option value="outros" <?= (($_POST['subject'] ?? '')==='outros')?'selected':''; ?>>Outros assuntos</option>
                            </select>
                        </div>
                        <div>
                            <label>Mensagem</label>
                            <textarea class="contact-textarea" name="message" required rows="6"><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
                        </div>
                        <button class="contact-submit" type="submit">Enviar Mensagem</button>
                    </form>
                </div>

                <!-- Informações -->
                <div class="contact-info-stack">
                    <section>
                        <h2 class="contact-info-title" style="font-size:1.9rem; font-weight:800; text-transform:uppercase; margin:0 0 1.75rem; letter-spacing:.05em;">Informações de Contato</h2>
                        <div class="contact-info-list" style="display:flex; flex-direction:column; gap:1.75rem;">
                            <?php
                            $infos = [
                                ['label'=>'E-mail','lines'=>['contato@urbanstreet.com.br','suporte@urbanstreet.com.br']],
                                ['label'=>'WhatsApp','lines'=>['(11) 98765-4321','Seg a Sex: 9h às 18h']],
                                ['label'=>'Telefone','lines'=>['(11) 3456-7890','Seg a Sex: 9h às 18h']],
                                ['label'=>'Endereço','lines'=>['Rua Augusta, 1234','Consolação - São Paulo/SP','CEP: 01304-001']],
                                
                            ];
                            foreach ($infos as $box): ?>
                                <div class="contact-info-block">
                                    <div class="contact-icon-circle">OK</div>
                                    <div>
                                        <h3><?= htmlspecialchars($box['label']) ?></h3>
                                        <?php foreach ($box['lines'] as $ln): ?>
                                            <p><?= htmlspecialchars($ln) ?></p>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </section>

                    <section class="contact-policy">
                        <h3>Política de Trocas</h3>
                        <ul>
                            <li>• Trocas em até 30 dias após a compra</li>
                            <li>• Produto sem uso e com etiqueta</li>
                            <li>• Nota fiscal obrigatória</li>
                            <li>• Primeira troca sem custo</li>
                        </ul>
                    </section>
                </div>
            </div>
        </div>
    </section>

    <section class="faq-section">
        <div class="container" style="max-width:960px; margin:0 auto; padding:0 1.25rem;">
            <h2 class="faq-title">Perguntas Frequentes</h2>
            <div class="faq-list">
                <?php
                $faqs = [
                    ['q'=>'Como acompanhar meu pedido?','a'=>'Após a confirmação do pagamento enviamos um código de rastreio por e-mail. Use-o no site da transportadora ou Correios.'],
                    ['q'=>'Qual o prazo de entrega?','a'=>'Depende da região: em média 7 a 15 dias úteis. Capitais e grandes centros recebem antes.'],
                    ['q'=>'Como funciona a troca?','a'=>'Você tem até 30 dias. Envie o número do pedido e motivo. A primeira troca é gratuita.'],
                ];
                foreach ($faqs as $f): ?>
                    <details>
                        <summary><?= htmlspecialchars($f['q']) ?></summary>
                        <p><?= htmlspecialchars($f['a']) ?></p>
                    </details>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</div>
