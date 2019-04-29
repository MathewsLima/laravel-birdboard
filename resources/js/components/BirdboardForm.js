class BirdboardForm {
    constructor(data) {
        this.originalData = {};
        this.originalData = JSON.parse(JSON.stringify(data));
        Object.assign(this, data);

        this.errors = {};
    }

    data() {
        return Object.keys(this.originalData).reduce((data, attribute) => {
            data[attribute] = this[attribute];

            return data;
        }, {});
    }

    submit(endpoint) {
        return axios.post(endpoint, this.data())
            .then(response => this.onSuccess(response))
            .catch(error => this.onFail(error));
    }

    onSuccess(response) {
        this.errors = {};

        return response;
    }

    onFail(error) {
        const {errors} = error.response.data;
        this.errors = errors;

        throw error;
    }

    reset() {
        Object.assign(this, this.originalData);
    }
}

export default BirdboardForm;
