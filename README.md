## Teste analista junior

Neste teste você dispõe de um cenário fictício, onde há um painel de monitoramento de ramais que contem alguns bugs que precisam ser corrigidos. Este painel também deverá ser melhorado, o minimo de melhorias que deverá ser acrescentado serão 3. Abaixo uma relação dos itens que deverão ser corrigidos:

- Os ramais offiline não são exibidos corretamente no painel, para corrigir você deverá exibir os ramais indisponiveis, fazendo com que o card do painel fique cinza e traga um icone circular no canto superior direito com a cor cinza mais escura. 
- Os ramais que estão em pausa no grupo de callcenter não estão sendo exibidos corretamente, para corrigir você deverá exibir os ramais que estão com com status de pausa, trazendo um icone circular no canto superior direito com a cor laranja.
- Os card deverão exibir os nomes dos agentes que estão no grupo de callcenter SUPORTE (arquivo lib\filas)

### MELHORIAS  
Após a correção destes itens, você deverá aplicar ao menos 3 (três) melhorias neste sistema.

### OBRIGATÓRIO  
O teste também contará com algumas atividades obrigatórias:
- Transformar o arquivo lib\ramais.php em uma classe e utiliza-lo neste sistema. Após a criação da classe o arquivo lib\ramais.php não deverá ser mais utilizado.
- Apesar dos registros serem estaticos, deverá ser criada uma base de dados utilizando mysql ou mariadb para armazenar as informações de cada ramal, como numero, nome, IP,  status do ramal no grupo de callcente (disponivel, pausa, offiline, etc).
- As informações da tela devem ser atualizadas a cada 10 segundos utilizando ajax e estas informações devem ser atualizadas na base de dados. Para verificar se está sendo atualizado na base de dados você poderá alterar as informações dos arquivos  lib\filas e lib\ramais

### IMPORTANTE
0. Você não podera utilizar frameworks, o código terá de ser 100% seu.
1. O arquivo lib\filas simula as informações de um grupo de callcenter  
2. O arquivo lib\ramais simula as informações dos ramais  
3. Estes arquivos se completam  
4. Estes arquivos NÃO devem unidos em um só arquivo  
5. Estes arquivos poderão ser alterados apenas para teste do AJAX  
6. Ao concluir o teste, deverá ser encaminhado um arquivo .zip contendo todo o código, dump da base de dados e instruções de instalação e a lista das melhorias aplicadas.  