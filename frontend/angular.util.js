angular.module('myService', [])
    .service('MS', function ($http) {
        this.actionHandler = async (func, options = {}) => {
            if (!options.selectorLoad) options.selectorLoad = 'body';
            try {
                $(options.selectorLoad).loading({ message: 'Aguarde ...' });
                let data = await func;
                await new Promise(resolve => {
                    $(options.selectorLoad).loading('stop');
                    this.notifSucess(options.msgNotf);
                    setTimeout(resolve, 500)
                });
                return data;
            } catch (e) {
                this.notifError(options.msgError);
                throw e;
            } finally {
                $(options.selectorLoad).loading('stop');
            }
        }
        this.notifSucess = (msg = null) => {
            if (msg === '') return;
            $.notify((msg || 'Ação realizada com sucesso'), "success");
        }
        this.notifError = (msg = null) => $.notify((msg || 'Ocorreu um erro inesperado'), "error");
    })