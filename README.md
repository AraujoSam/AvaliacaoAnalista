# Desafio

- Este repositório possui 2 desafios em .Net, o desafio está dentro do _Program.cs_ de cada projeto.
- Para enviar a resposta, abra uma Pull Request para este repositório.

# Questionário

1. Cite 6 fatores do Twelve-Factor App e explique cada um deles.

**Resposta:**
Reconheço que não estava familiarizado com o conceito de Twelve-Factor App; no entanto, tomei a liberdade de pesquisar para me aprimorar e entendi que se trata de uma metodologia com doze boas práticas para construir aplicações web/SaaS fáceis de implantar, escalar e manter, principalmente em ambientes de nuvem. Ela é usada para que a mesma aplicação rode de forma previsível em qualquer ambiente (local, homologação, produção) e possa ser replicada sem ajustes manuais. Seis desses fatores, com exemplos no contexto de PHP/Laravel:

- **I. Codebase (base de código):** uma única base de código versionada (um repositório Git) por aplicação, da qual saem vários deploys (dev, hml, prd).
- **II. Dependencies (dependências):** todas as dependências são declaradas explicitamente e isoladas, sem depender do que "já está instalado" no servidor; no Laravel, isso é feito pelo `composer.json` e pelo `composer.lock`.
- **III. Config (configuração):** tudo o que muda entre ambientes (credenciais, URLs, chaves) fica em variáveis de ambiente, e não no código — no Laravel, o arquivo `.env`.
- **IV. Backing services (serviços de apoio):** banco de dados, cache, filas e serviços de e-mail são tratados como recursos anexados, que podem ser trocados apenas mudando a configuração (ex.: trocar o servidor do banco alterando o `DB_HOST`).
- **VI. Processes (processos):** a aplicação roda como processos sem estado (*stateless*); dados que precisam persistir, como sessões e arquivos, ficam em serviços externos (banco, Redis, storage), permitindo subir várias instâncias em paralelo.
- **X. Dev/prod parity (paridade entre ambientes):** os ambientes de desenvolvimento, homologação e produção devem ser o mais parecidos possível (mesmas versões e mesmos serviços), reduzindo erros do tipo "na minha máquina funciona".

2. Quais são as principais camadas no desenvolvimento de um software?

**Resposta:**
De forma geral, um software costuma ser dividido em três camadas principais, cada uma com uma responsabilidade:

- **Camada de apresentação (interface):** é o que o usuário vê e com o que interage como telas, formulários e componentes. No meu dia a dia, são as views Blade do Laravel, o HTML/CSS e o JavaScript/jQuery do frontend.
- **Camada de negócio (aplicação):** onde ficam as regras do sistema como validações, cálculos, permissões e fluxos. No Laravel, ficam nos controllers, nos Form Requests, nas classes de serviço e nas policies.
- **Camada de dados (persistência):** responsável por gravar e buscar as informações no banco de dados. No Laravel, são os models do Eloquent, as migrations e as consultas SQL (no meu caso, frequentemente em Oracle).

O padrão MVC (Model-View-Controller), usado pelo Laravel, é uma forma de organizar essas camadas. Quando o backend é exposto como API (como nas APIs REST em Lumen com as quais trabalhei), a camada de apresentação passa a ser um cliente separado que consome essa API.

3. Diferencie Arquitetura SOA X Arquitetura microserviços.

**Resposta:**
Tenho experiência com monolitos, ou seja, sistemas em que todos os módulos (cadastros, regras de negócio, telas, integrações) fazem parte de uma única aplicação, com um único deploy e normalmente um banco de dados compartilhado. A arquitetura de microsserviços, por outro lado, divide o sistema em vários serviços pequenos e independentes, cada um responsável por uma parte específica do negócio, com seus próprios dados e seu próprio deploy, comunicando-se por APIs ou mensageria; isso permite escalar e evoluir cada parte separadamente, ao custo de maior complexidade operacional.

Também atuei em uma plataforma corporativa organizada dessa forma distribuída, tanto no frontend quanto no backend:

- **Frontend em micro frontends:** construído com Vue 3 e Webpack Module Federation. Uma aplicação "orquestradora" concentra login, sessão, escolha da empresa, layout e componentes visuais compartilhados, e carrega em tempo de execução cada módulo de negócio (como solicitações e administração), que fica em um repositório próprio e tem deploy independente.
- **Backend segmentado por módulo:** cada módulo possui sua própria API em Laravel, com repositório, pipeline e contêiner próprios, e dados isolados em um banco ou *schema* próprio no PostgreSQL. A autenticação é feita por token JWT, e cada serviço consulta o serviço administrativo via REST para verificar as permissões do usuário antes de executar uma operação.
- **Entrega:** cada serviço passa por um pipeline no GitLab CI/CD (lint, análise estática, verificação de segurança, testes com cobertura mínima, build da imagem Docker) e é implantado de forma independente em Docker Swarm.

Nessa plataforma, desenvolvi telas e fluxos do micro frontend de solicitações (gestão de tipos e de árvore de assuntos, exportação de dados processada no servidor e exibição de ações conforme as permissões do usuário), funcionalidades do orquestrador (tela de escolha de empresa, renovação de sessão e integração de um novo módulo remoto) e a API do módulo de agenda dos gestores (cadastro, edição e consulta de agendas, exportação em PDF/CSV, importação de eventos do Outlook via Microsoft Graph e testes unitários e de integração). Na prática, vivenciei tanto as vantagens dessa abordagem (times e módulos evoluindo e sendo publicados de forma independente) quanto seus desafios: a necessidade de alinhar contratos e nomenclaturas entre frontend e backend, a dependência de outros serviços (como o de permissões) e o cuidado para manter a experiência visual consistente entre os módulos.

Já a arquitetura SOA (Service-Oriented Architecture) eu não conhecia; pesquisei para incrementar meu conhecimento e entendi que ela é anterior aos microsserviços e também organiza o sistema em serviços, porém com foco em reutilização em toda a empresa. As principais diferenças:

- **Tamanho dos serviços:** na SOA, os serviços são maiores e mais genéricos (ex.: um serviço de "Clientes" usado por vários sistemas da empresa); nos microsserviços, são menores e focados em uma única responsabilidade.
- **Comunicação:** a SOA costuma usar um barramento central (ESB — Enterprise Service Bus), que concentra roteamento, transformação e regras, geralmente com SOAP/XML; nos microsserviços, a comunicação é direta e leve (REST, gRPC, mensageria), e a inteligência fica nos próprios serviços.
- **Dados:** na SOA é comum os serviços compartilharem bancos de dados; nos microsserviços, cada serviço é dono dos seus dados.
- **Deploy e governança:** a SOA tende a ter governança centralizada; nos microsserviços, cada time implanta seu serviço de forma independente.

4. Qual é o objetivo de um API Management na arquitetura de serviços distribuídos? Cite vantagens e desvantagens.

**Resposta:**
Não estava familiarizado com o termo API Management; após pesquisar, consegui compreender o conceito e o papel desse padrão. Um API Management é uma camada que fica na frente das APIs de uma organização e centraliza o controle sobre elas. Em vez de cada API implementar sozinha autenticação, limites de uso e monitoramento, as requisições passam primeiro por um gateway que aplica essas regras de forma padronizada e depois encaminha para o serviço correto. Normalmente, ele também oferece um portal para que desenvolvedores conheçam e testem as APIs disponíveis. Exemplos de ferramentas: Azure API Management, AWS API Gateway, Google Apigee, Kong e WSO2.

**Vantagens:**
- Segurança centralizada (autenticação, chaves de acesso, políticas de acesso).
- Controle de tráfego, como limites de requisições por cliente (*rate limiting*).
- Monitoramento, métricas e logs de uso em um único lugar.
- Versionamento e publicação organizada das APIs, com documentação e portal para desenvolvedores.
- Possibilidade de transformar requisições/respostas e aplicar cache sem alterar os serviços.

**Desvantagens:**
- Adiciona um ponto a mais na requisição, o que gera um pouco de latência.
- Se não houver alta disponibilidade, pode se tornar um ponto único de falha.
- Custo de licença/infraestrutura e risco de dependência de um fornecedor específico.
- Mais uma peça para configurar, manter e governar.

Com esse entendimento, consegui identificar pequenos traços desse padrão nos projetos em que atuo e atuei, embora nenhum deles utilize uma ferramenta de API Management completa. Nesses sistemas, as APIs ficam expostas sob um único endereço por ambiente, com roteamento por caminho para cada serviço, e a autenticação é centralizada em um serviço que emite tokens OAuth2. No entanto, cada API ainda valida o token e as permissões individualmente, e não há controle centralizado de limite de requisições ou métricas de uso, justamente as responsabilidades que um API Management concentraria em um único ponto.

5. Qual a diferença entre uma Struct e uma Class?

**Resposta:**
Faz algum tempo que não trabalho diretamente com esses conceitos de orientação a objetos em linguagens como C#, mas revisei o tema. A principal diferença está na forma como os dados são armazenados e copiados:

- **Struct** é um **tipo de valor**: ao atribuir uma struct a outra variável ou passá-la para um método, todo o conteúdo é copiado, e alterar a cópia não altera o original. Não suporta herança (embora possa implementar interfaces), não pode ser nula (a menos que declarada como `Nullable`) e é indicada para dados pequenos e simples, como coordenadas, datas ou valores monetários (`DateTime` e `int` são structs no .NET).
- **Class** é um **tipo de referência**: a variável guarda uma referência para o objeto, que fica na memória gerenciada (*heap*). Ao atribuir a outra variável, as duas apontam para o mesmo objeto, então uma alteração é vista por ambas. Suporta herança, pode ser nula e é indicada para entidades mais complexas e com comportamento.

Fazendo um paralelo com o PHP: um array se comporta de forma parecida com uma struct (é copiado ao ser atribuído), enquanto um objeto se comporta como uma class (as variáveis compartilham a mesma instância).

6. Explique a grande diferença entre .NET e .NET CORE.

**Resposta:**
Não estou familiarizado com o ecossistema .NET, mas conceitualmente entendo que:

- O **.NET Framework** é a plataforma original da Microsoft (lançada em 2002), que roda **apenas no Windows**, é fortemente integrada ao sistema operacional e hoje recebe somente correções (a última versão é a 4.8.x).
- O **.NET Core** (lançado em 2016) é uma reescrita **multiplataforma** (Windows, Linux e macOS), **open source**, modular e com desempenho superior, pensada para nuvem, contêineres e microsserviços. Permite também que versões diferentes convivam na mesma máquina.

A partir de 2020, com o .NET 5, a Microsoft unificou a plataforma e deixou de usar o termo "Core": o ".NET" atual (como o .NET 8 utilizado neste desafio) é a continuação do .NET Core.

7. Quais as principais diferenças entre REST e GRPC?

**Resposta:**
O REST é um estilo de arquitetura para APIs sobre HTTP, extremamente difundido e um dos meios mais conhecidos de construir uma API. Ele organiza a API em recursos acessados por URLs e verbos HTTP (GET, POST, PUT, DELETE), seguindo o modelo de requisição e resposta, normalmente trafegando dados em JSON, que é legível por pessoas e funciona diretamente em qualquer navegador. É o padrão com o qual trabalho no dia a dia, construindo e consumindo APIs REST em Laravel e Lumen.

Particularmente, não estou familiarizado com o gRPC, mas pesquisei sobre o tema e consegui compreender o seu conceito e o que o diferencia do REST. O gRPC é um framework criado pelo Google para chamadas de procedimento remoto (RPC): em vez de acessar recursos por URL, o cliente chama métodos de um serviço remoto como se fossem funções locais. As principais diferenças:

- **Formato dos dados:** o gRPC não usa JSON, e sim **Protocol Buffers**, um formato binário mais compacto e mais rápido de processar, porém não legível diretamente.
- **Contrato:** no gRPC, os serviços e mensagens são definidos em um arquivo `.proto`, a partir do qual o código do cliente e do servidor é gerado automaticamente; no REST, o contrato é mais flexível (geralmente documentado com OpenAPI/Swagger).
- **Transporte:** o gRPC usa HTTP/2, que permite várias chamadas simultâneas na mesma conexão.
- **Streaming:** além do modelo de uma requisição e uma resposta, o gRPC permite enviar e receber fluxos contínuos de mensagens (do cliente, do servidor ou nos dois sentidos). Antes da pesquisa, eu imaginava que o gRPC trabalhava com envio e recebimento em "partes"; entendi que essa ideia corresponde, na verdade, a esse recurso de streaming, e não ao funcionamento padrão de todas as chamadas.
- **Uso típico:** o REST é mais usado em APIs públicas e na comunicação com navegadores; o gRPC é mais usado na comunicação interna entre serviços (por exemplo, entre microsserviços), onde desempenho importa mais e o suporte direto em navegadores é limitado.

8. Explique como funciona um gerenciamento de rotas de uma SPA.

**Resposta:**
Já trabalhei com SPAs em Vue 3 utilizando o Vue Router, mas, sendo sincero, eu o utilizava mais como ferramenta (cadastrando rotas e telas) do que conhecendo a fundo o seu funcionamento interno. Para responder a esta pergunta, revisei o tema e entendi que o processo funciona da seguinte forma.

Em uma SPA (Single Page Application), o servidor entrega uma única página HTML e, a partir daí, a navegação passa a ser controlada pelo JavaScript no próprio navegador, sem recarregar a página inteira. O gerenciador de rotas (*router*) mantém um mapa que associa cada endereço a uma tela ou componente; por exemplo, o endereço de listagem de clientes corresponde ao componente que exibe essa listagem.

Quando o usuário clica em um link, o router impede que o navegador faça uma nova requisição ao servidor, atualiza o endereço exibido na barra do navegador por meio da History API (ou, em um modo mais antigo, alterando apenas o trecho após o símbolo `#`) e renderiza somente o componente correspondente. Da mesma forma, quando o usuário usa os botões de voltar e avançar, o navegador avisa o router, que exibe a tela do endereço correspondente.

Como essas rotas existem apenas no frontend, o servidor precisa estar configurado para devolver sempre a mesma página inicial, independentemente do endereço acessado; caso contrário, ao atualizar a página ou abrir um link direto para uma tela interna, o servidor retornaria um erro de página não encontrada. Esse cuidado costuma ser resolvido na configuração do servidor web ou, em um projeto Laravel, com uma rota de *fallback* que entrega a página da SPA.

Além do básico, bibliotecas como o Vue Router e o React Router oferecem recursos como parâmetros nas rotas (por exemplo, o identificador de um cliente), rotas aninhadas, carregamento sob demanda das telas e *guards*, que permitem verificar se o usuário está autenticado ou tem permissão antes de abrir uma tela. Ainda assim, essa verificação no frontend serve apenas para a experiência do usuário: a autorização de fato precisa ser validada no backend.

9. Falando sobre DevOps, comente o que conhece sobre.

**Resposta:**
Entendo DevOps como uma cultura e um conjunto de práticas que aproximam as equipes de desenvolvimento (Dev) e de operações (Ops), com o objetivo de entregar software com mais frequência, rapidez e segurança. Mais do que uma área isolada, é uma forma de trabalhar que envolve automatizar e acompanhar todo o ciclo de vida do software, incluindo:

- Versionamento de código e revisão por Merge/Pull requests.
- Integração e entrega contínuas (CI/CD), com pipelines de build, testes e deploy automatizados.
- Infraestrutura como código para criar e configurar servidores de forma reproduzível.
- Contêineres e orquestração (Docker, Kubernetes).
- Monitoramento, logs e alertas, para identificar problemas em produção rapidamente.
- Segurança integrada ao processo desde o início, prática conhecida como DevSecOps.

O ponto central é a responsabilidade compartilhada: quem desenvolve também se preocupa com o funcionamento do software em produção, e o feedback desse funcionamento volta rapidamente para o desenvolvimento.

10. Explique sobre um método agile.

**Resposta:**
Tive a oportunidade de trabalhar com duas metodologias ágeis, o Scrum e o Kanban, aplicadas de forma adequada às necessidades e particularidades dos projetos e equipes de que participei.

O **Scrum** organiza o trabalho em ciclos curtos e de duração fixa, chamados *sprints* (geralmente de uma a quatro semanas; minha equipe trabalha com sprints de duas semanas). Cada sprint segue eventos bem definidos: a *planning*, em que a equipe seleciona do *backlog* o que será entregue; as *dailies*, reuniões diárias e rápidas para acompanhar o andamento e remover impedimentos; a *review*, em que o resultado é apresentado e validado; e a *retrospectiva*, em que a equipe avalia o próprio processo para identificar o que pode ser melhorado. O Scrum também define papéis: o *Product Owner*, responsável por priorizar o backlog conforme o valor para o negócio; o *Scrum Master*, que facilita o processo; e o time de desenvolvimento, que se auto-organiza para realizar a entrega.

O **Kanban**, por sua vez, foca em visualizar e otimizar o fluxo contínuo de trabalho, sem sprints de duração fixa nem papéis obrigatórios. As demandas são representadas em um quadro com colunas que refletem as etapas do processo (por exemplo, *to do*, *doing* e *done*), e cada pessoa "puxa" a próxima tarefa quando termina a anterior. Um elemento importante é o limite de tarefas em andamento (WIP — *work in progress*) por coluna, que evita sobrecarga e evidencia gargalos. As prioridades continuam sendo definidas pelo responsável pelo produto ou pela equipe, mas cada integrante tem mais autonomia para gerenciar o próprio fluxo de trabalho.

11. Comente sobre CI e CD e algumas ferramentas do dia a dia.

**Resposta:**
**CI (Integração Contínua)** é a prática de integrar as alterações de código ao repositório principal com frequência, e a cada integração executar automaticamente o build, os testes e verificações de qualidade, para identificar problemas o quanto antes.

**CD** pode significar **Entrega Contínua** (*Continuous Delivery*), em que toda alteração aprovada fica pronta para ser publicada, mas a publicação em produção depende de uma aprovação manual; ou **Implantação Contínua** (*Continuous Deployment*), em que a publicação em produção também é automática.

Minha vivência prática é com pipelines do GitLab CI/CD, configuradas no arquivo `.gitlab-ci.yml`, em que cada merge request ou envio para as branches dos ambientes (dev, homologação, produção) dispara etapas automatizadas. Algumas ferramentas comuns nesse contexto:

- **Pipelines:** GitLab CI/CD, GitHub Actions, Jenkins e Azure DevOps.
- **Qualidade e testes:** PHPUnit/Pest, SonarQube e ferramentas de análise estática como PHPStan.
- **Empacotamento e deploy:** Docker, registros de imagens e ferramentas como Argo CD para implantação em Kubernetes.

12. Qual a diferença entre Docker e Containers.

**Resposta:**
**Contêiner** é o conceito: um processo isolado que roda com tudo de que a aplicação precisa (código, bibliotecas, dependências e configurações), compartilhando o kernel do sistema operacional da máquina, o que o torna muito mais leve que uma máquina virtual. Um contêiner é criado a partir de uma **imagem**, que funciona como um modelo empacotado da aplicação. Com isso, o sistema roda da mesma forma em qualquer ambiente, evitando conflitos de configuração entre a máquina do desenvolvedor, a homologação e a produção.

**Docker** é a ferramenta mais popular para criar imagens (a partir de um `Dockerfile`), executar e gerenciar contêineres. Ou seja, o Docker é uma ferramenta; o contêiner é a tecnologia que ela utiliza.

Uma comparação também comum é entre Docker e Kubernetes: enquanto o Docker cria e executa contêineres (geralmente em uma única máquina, inclusive localmente com o Docker Compose), o Kubernetes é um **orquestrador** que gerencia muitos contêineres distribuídos em várias máquinas de um cluster cuidando de escalar a aplicação, reiniciar contêineres que falham, distribuir a carga e atualizar versões sem indisponibilidade.

13. Qual a diferença entre Kubernetes e Openshift?

**Resposta:**
Não conhecia o OpenShift, mas pesquisei e entendi conceitualmente a diferença. O **Kubernetes** é o orquestrador de contêineres open source (descrito na resposta anterior), que fornece a base: agendamento, escalabilidade, recuperação automática e rede entre os contêineres. Ele é bastante flexível, mas muitos recursos precisam ser escolhidos, instalados e integrados pela própria equipe.

O **OpenShift**, da Red Hat, é uma plataforma corporativa construída **sobre o Kubernetes**, que entrega esses recursos já integrados e com suporte comercial. Entre as principais diferenças:

- **Interface e experiência do desenvolvedor:** possui um console web completo e ferramentas para gerar imagens diretamente a partir do código-fonte.
- **CI/CD e registro integrados:** já inclui pipelines e um registro interno de imagens.
- **Segurança mais restritiva por padrão:** por exemplo, impede que contêineres rodem como usuário *root*.
- **Suporte e atualizações:** conta com suporte comercial e um ciclo de versões testado pela Red Hat, o que é valorizado em grandes empresas.

Em resumo: o Kubernetes é o motor; o OpenShift é uma distribuição empresarial completa construída em torno desse motor.

14. Quais as vantagens e desvantagens sobre API e quais preocupações devemos ter quando escolhemos essa abordagem?

**Resposta:**
**Vantagens:** a separação de responsabilidades em um sistema, apartando a parte visual (frontend) da parte lógica (backend), o que segue uma arquitetura e um padrão muito frequentes no mercado e facilita a manutenção. Além disso, a mesma API pode ser reutilizada por diferentes clientes (site, aplicativo mobile, integrações com outros sistemas), e cada lado pode evoluir, ser implantado e escalado de forma independente.

**Desvantagens:** a principal preocupação é a segurança, já que a API expõe dados e operações pela rede. Mas também existem outros custos:

- **Latência e dependência da rede:** cada interação vira uma requisição HTTP, o que adiciona tempo e possibilidade de falhas.
- **Maior complexidade:** passa a haver dois projetos para manter, implantar e monitorar, além de questões como autenticação por token e CORS.
- **Versionamento:** depois que outros sistemas consomem a API, mudanças no contrato (nomes de campos, formatos) podem quebrar esses clientes, exigindo versionamento e compatibilidade com versões anteriores.

**Preocupações ao escolher essa abordagem:** definir bem o contrato e documentá-lo (ex.: OpenAPI/Swagger), planejar o versionamento, padronizar respostas e códigos de erro, tratar falhas e tempos limite do lado do cliente, controlar o volume de requisições e monitorar o uso e o desempenho.

15. Como conseguimos garantir um nível de segurança satisfatório no uso de APIS?

**Resposta:**
A segurança de uma API depende de várias camadas de proteção combinadas. As principais práticas são:

- **Comunicação criptografada:** usar sempre HTTPS (TLS).
- **Autenticação:** garantir que se sabe quem está chamando a API, com padrões como OAuth 2.0 e tokens (JWT) — no Laravel, com Sanctum ou Passport.
- **Autorização:** verificar, em toda requisição e no servidor, se o usuário tem permissão para acessar aquele recurso específico (no Laravel, com policies e gates). Essa validação deve acontecer no momento do uso, e não depender do caminho que o usuário percorreu até chegar ali.
- **Validação de entrada:** validar e sanitizar todos os dados recebidos (no Laravel, com Form Requests) e usar consultas parametrizadas ou o ORM para evitar SQL Injection.
- **Limite de requisições (*rate limiting*):** restringir o número de chamadas por cliente, para mitigar abusos e ataques de força bruta (no Laravel, com o middleware `throttle`).
- **Exposição mínima de dados:** retornar apenas os campos necessários e não exibir detalhes internos em mensagens de erro (ex.: `APP_DEBUG=false` em produção).
- **Proteção de segredos:** manter chaves e credenciais em variáveis de ambiente ou cofres de segredos, nunca no código.
- **CORS configurado corretamente:** permitir chamadas apenas das origens autorizadas.
- **Monitoramento e atualização:** registrar logs de acesso, gerar alertas para comportamentos suspeitos e manter as dependências atualizadas. O guia OWASP API Security Top 10 é uma boa referência dos riscos mais comuns.

16. Para que serve uma arquitetura de mensagerias?

**Resposta:**
Ainda não utilizei mensageria em projetos, mas pesquisei e passei a conhecer o conceito. Uma arquitetura de mensageria permite que sistemas ou serviços se comuniquem de forma **assíncrona**, por meio de um intermediário chamado *message broker* (como RabbitMQ, Apache Kafka, Amazon SQS ou Azure Service Bus). Em vez de um sistema chamar o outro diretamente e esperar a resposta, o produtor publica uma mensagem no broker e segue seu fluxo; o consumidor processa essa mensagem quando estiver disponível.

Ela serve para:

- **Desacoplar sistemas:** o produtor não precisa conhecer nem esperar o consumidor.
- **Aumentar a resiliência:** se o consumidor estiver fora do ar, as mensagens ficam guardadas na fila e são processadas depois, com possibilidade de novas tentativas em caso de erro.
- **Absorver picos de demanda:** as mensagens se acumulam na fila e são processadas no ritmo que os consumidores suportam.
- **Distribuir eventos:** no modelo *publish/subscribe*, um mesmo evento (ex.: "pedido criado") pode ser recebido por vários serviços interessados, como faturamento, estoque e notificações.

Conceitualmente, é uma ideia próxima à das filas do Laravel (*queues* e *jobs*), em que tarefas demoradas, como o envio de e-mails, são processadas em segundo plano.

17. Explique a estratégia SAGA em arquitetura de microservice.

**Resposta:**
Embora eu tenha atuado em uma plataforma com backend segmentado em serviços (descrita na pergunta 3), nunca precisei aplicar o padrão SAGA na prática, então pesquisei sobre o tema para compreendê-lo. Em um monolito, uma operação que altera várias tabelas pode ser feita dentro de uma única transação no banco de dados: se algo falhar, tudo é desfeito (*rollback*). Em microsserviços, cada serviço tem seu próprio banco, então não existe uma transação única que envolva todos eles.

O padrão **SAGA** resolve isso dividindo a operação em uma sequência de **transações locais**, uma em cada serviço. Se uma das etapas falhar, são executadas **ações de compensação** que desfazem, de forma lógica, as etapas que já tinham sido concluídas.

Exemplo: em uma compra, o serviço de pedidos cria o pedido, o serviço de pagamentos cobra o cliente e o serviço de estoque reserva o produto. Se não houver estoque, a saga executa as compensações: estorna o pagamento e cancela o pedido.

Existem duas formas de coordenar uma saga:

- **Coreografia:** cada serviço publica eventos e os outros reagem a eles, sem um coordenador central (normalmente usando mensageria).
- **Orquestração:** um serviço orquestrador central comanda cada etapa e decide quando executar as compensações.

Relacionando com a minha experiência: na plataforma em que atuei, cada serviço tem seus próprios dados, e as operações de gravação (como o cadastro, a edição e o cancelamento de agendas, junto com o registro de auditoria) são feitas dentro de transações locais no banco do próprio serviço. A comunicação entre os serviços, por sua vez, era basicamente de consulta, como a verificação de permissões do usuário em outro serviço. Por isso, não havia operações que precisassem gravar dados em mais de um serviço ao mesmo tempo, que é justamente o cenário em que a SAGA se torna necessária. Se, por exemplo, uma solicitação precisasse ser registrada em um serviço e, ao mesmo tempo, gerar automaticamente uma agenda em outro, seria preciso adotar esse padrão para garantir a consistência entre os dois.

18. Descreva o seu entendimento sobre GitOps utilizando Kubernetes.

**Resposta:**
Meu conhecimento sobre GitOps ainda é bastante limitado, mas, pelo que pesquisei, entendo que é uma prática em que o **repositório Git é a única fonte da verdade** sobre como a infraestrutura e as aplicações devem estar configuradas. No caso do Kubernetes, os arquivos que descrevem o estado desejado do cluster (manifestos YAML ou charts do Helm, com quais aplicações rodam, quais versões e quantas réplicas) ficam versionados no Git.

Uma ferramenta instalada dentro do cluster, como **Argo CD** ou **Flux**, compara continuamente o que está no repositório com o que está de fato rodando no cluster e, quando encontra diferenças, aplica as mudanças automaticamente para deixá-los iguais (processo chamado de reconciliação).

Assim, qualquer alteração no ambiente é feita por meio de um merge request no repositório, o que traz:

- **Rastreabilidade:** todo o histórico de mudanças fica registrado no Git.
- **Revisão:** alterações passam por aprovação antes de chegarem ao ambiente.
- **Rollback simples:** voltar uma versão é reverter um commit.
- **Consistência:** alterações manuais feitas diretamente no cluster são detectadas e corrigidas.

Esse fluxo de merge requests se aproxima da forma como já trabalho com versionamento no GitLab, aplicado agora à infraestrutura.

19. Descreva detalhadamente algum case de sucesso em que você atuou diretamente no desenvolvimento para solução de algum problema, cite tecnologias e os desafios enfrentados.

**Resposta:**

**Case: vazamento de permissões entre empresas num portal de solicitações**

Atuei num portal web de solicitações de serviços, organizado em módulos, em que um mesmo usuário pode representar várias empresas, cada uma com permissões diferentes. Fui um dos primeiros a identificar e investigar uma falha: ao voltar pela seta do próprio navegador e trocar de empresa, o usuário ficava com as permissões das duas empresas somadas. Durante o diagnóstico, vi que a causa era a combinação de dois fatores: a empresa selecionada ficava guardada na sessão, e o cache de navegação do navegador restaurava a tela com o estado antigo.

Propus uma primeira correção: limpar a sessão sempre que o usuário retornasse ao painel inicial. Essa solução chegou a ser aplicada, mas foi removida depois, quando ficou claro que não era eficiente: muitos usuários acessavam os formulários direto por links salvos e nunca passavam pelo painel. Por questões de prioridade, a demanda passou para um desenvolvedor sênior, e eu acompanhei a solução definitiva. Ela consistiu em exigir que o usuário escolhesse a empresa antes de qualquer tipo de solicitação, conferir o acesso no servidor sempre que o formulário é aberto e detectar as voltas pelo navegador para descartar o estado antigo.

**Tecnologias:** PHP 7.2, com Laravel 6 no portal web e Lumen 6 nas APIs REST; JavaScript (jQuery); banco de dados Oracle (SQL).

**Desafios:**

- Reproduzir um erro que só acontecia numa combinação específica de usuário e navegação.
- Tratar regras de acesso diferentes em cada módulo.
- Lidar com diferenças de configuração entre os ambientes de desenvolvimento e homologação.
- Ajustar exceções para perfis internos que não representam uma empresa.

**Resultado e aprendizado:** a falha de permissões foi eliminada e o fluxo ficou mais claro para o usuário. Foi um dos meus primeiros contatos com um cenário muito comum no desenvolvimento web: o cache desatualizado, em que o navegador ou a sessão mostram um estado que não corresponde mais à realidade. Ver minha primeira solução ser aplicada e depois removida me ensinou que uma regra de autorização não pode depender do caminho que o usuário percorre: ela precisa ser validada no momento do uso.
