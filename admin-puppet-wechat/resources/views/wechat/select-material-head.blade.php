<link rel="stylesheet" href="{{ asset('/css/index.css') }}">
<style>
  .mf-tabs--left {
    position: relative;
    padding-left: 120px;
  }

  .mf-tabs--left .mf-tabs__header.is-left {
    float: none;
    margin-right: 10px;
    position: absolute;
    left: 0px;
    top: 0px;
  }
</style>
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    .asset-list {
        margin-top: 20px;
        column-count: 2;
        column-gap: 0;
        list-style: none;
    }
    .item {
        position: relative;
        break-inside: avoid;
        margin-bottom: 20px;
        width: 280px;
        height: auto;
        border: 1px solid #ddd;
        cursor: pointer;
    }
    .item.active:before {
        content: "";
        position: absolute;
        left: 0px;
        top: 0px;
        width: 100%;
        height: 100%;
        z-index: 9;
        background-color: rgba(0, 0, 0, 0.4);
    }
    .item.active:after {
        position: absolute;
        left: 50%;
        top: 50%;
        z-index: 10;
        transform: translate3d(-50%,-50%,0);
        font-size: 40px;
        color: #409EFF;
        content: "\E62D";
        font-family: element-icons !important;
        speak: none;
        font-style: normal;
        font-weight: 400;
        font-variant: normal;
        text-transform: none;
        line-height: 1;
        vertical-align: baseline;
        display: inline-block;
        -webkit-font-smoothing: antialiased;
    }
    .item .content {
      padding: 10px;
    }
    .content .avatar {
        position: relative;
        width: 100%;
        height: 150px;
        background-repeat: no-repeat;
        background-size: cover;
        background-position: center;
    }
    .content .avatar .title {
      position: absolute;
      left: 0px;
      bottom: 0px;
      width: 100%;
      padding: 10px;
      line-height: 1;
      white-space: nowrap;
      text-overflow: ellipsis;
      overflow: hidden;
      color: #fff;
      background-color: rgba(0, 0, 0, 0.3);
    }
    .item .line {
      padding: 10px;
      border-top: 1px solid #ddd;
      height: 60px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .item .line .txt {
      width: 200px;
      white-space: nowrap;
      text-overflow: ellipsis;
      overflow: hidden;
    }
    .item .line .avatar {
      width: 40px;
      height: 40px;
      background-repeat: no-repeat;
      background-size: cover;
      background-position: center;
    }
    .v-modal{
        display: none;
    }
    .mf-dialog__wrapper{
        display: none;
    }
    .content{
        min-height:170px;
    }
</style>