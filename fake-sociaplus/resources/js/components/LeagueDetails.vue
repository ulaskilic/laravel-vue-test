<template>
    <div>
        <v-row>
            <v-col>
                <v-btn color="primary">Tüm ligi oynat</v-btn>
                <v-btn color="primary">Sonraki Haftayı Oynat</v-btn>
                <v-btn color="danger">Ligi Sıfırla</v-btn>
            </v-col>
        </v-row>
        <v-spacer/>
        <v-data-table :headers="table.headers" :loading="table.loading" :items="table.data">
            <template v-slot:top>
                <v-toolbar-title>Takım Listesi</v-toolbar-title>
                <v-divider class="mx-4" inset vertical/>
                <v-spacer/>
                <v-dialog max-width="500px" v-model="dialog.show">
                    <template v-slot:activator="{on, attrs}">
                        <v-btn color="primary" dark v-bind="attrs" v-on="on">
                            Yeni Takım Ekle
                        </v-btn>
                    </template>
                    <v-card>
                        <v-card-title>
                            <span class="headline">Takım Ekle/Düzenle</span>
                        </v-card-title>
                        <v-card-text>
                            <v-container>
                                <v-row>
                                    <v-col cols="12">
                                        <v-text-field v-model="dialog.data.name" label="Takım İsmi"/>
                                    </v-col>
                                </v-row>
                            </v-container>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer/>
                            <v-btn text @click="close">Kapat</v-btn>
                            <v-btn text @click="save">Kaydet</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </template>

            <template v-slot:item.name="{item}">
                <router-link :to="`/leagues/${item.id}/teams`">{{item.name}}</router-link>
            </template>
            <template v-slot:item.actions="{item}">
                <v-icon
                    small
                    class="mr-2"
                    @click="editItem(item)"
                >
                    mdi-pencil
                </v-icon>
                <v-icon
                    small
                    @click="deleteItem(item.id)"
                >
                    mdi-delete
                </v-icon>
            </template>
        </v-data-table>
    </div>
</template>

<script>
export default {
    data() {
        return {
            dialog: {
                show: false,
                data: {
                    name: '',
                }
            },
            table: {
                loading: false,
                headers: [
                    {text: 'Takım İsmi', value: 'name'},
                    {text: 'Oynadığı Lig', value: 'league.name'},
                    {text: 'Güncelleme', value: 'updated_at'},
                    {text: 'Aksiyonlar', value: 'actions', sortable: false},
                ],
                data: []
            }
        }
    },
    computed: {
      leagueId() {
          return this.$route.params.league_id
      }
    },
    mounted() {
        this.refresh()
    },
    methods: {
        async save() {
            if(this.dialog.data.id) {
                await this.$api.team.update(this.leagueId, this.dialog.data.id, {name: this.dialog.data.name});
            } else {
                await this.$api.team.create(this.leagueId, {name: this.dialog.data.name})
            }

            await this.close();
            await this.refresh();
        },
        async editItem(item) {
            this.dialog.data = item;
            this.dialog.show = true;
        },
        async deleteItem(id) {
            this.table.loading = true;
            await this.$api.league.delete(id);
            await this.refresh();
        },
        async close() {
            this.dialog.show = false;
            this.dialog.data = {}
        },
        async refresh() {
            this.table.loading = true;
            const {data} = await this.$api.team.list(this.leagueId);
            this.table.data = data;
            this.table.loading = false;
        }
    }
}
</script>
